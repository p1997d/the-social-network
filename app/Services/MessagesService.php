<?php

namespace App\Services;

use App\Models\File;
use App\Models\Dialog;
use App\Models\Message;
use App\Models\MessageFile;
use App\Models\User;
use App\Models\Chat;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MessagesService
{
    /**
     * Получает список вложений в сообщении
     *
     * @param string $attachmentsArray
     * @param string $type
     * @return array
     */
    public static function getAttachments($attachmentsArray, $type = null)
    {
        if (!$attachmentsArray) {
            return [];
        }

        $attachmentIds = json_decode($attachmentsArray);
        $attachments = File::whereIn('id', $attachmentIds)->get();

        if ($type) {
            $attachments = $attachments->filter(function ($item) use ($type) {
                return explode('/', $item->type)[0] == $type;
            });
        }

        return $attachments;
    }

    /**
     * Получает счетчик непрочитанных сообщений
     *
     * @return int|null
     */
    public static function getUnreadMessagesCount()
    {
        if (Auth::guest()) {
            return null;
        }

        $user = User::find(Auth::id());

        $unreadMessagesCount = $user->dialogsAndChatsWithMessages()->filter(function ($item) {
            return $item->messages()->where('author', '!=', auth()->user()->id)->whereNull('viewed_at')->filter(function ($item) {
                return class_basename($item) !== 'ChatSystemMessage';
            })->isNotEmpty();
        })->count();

        return $unreadMessagesCount;
    }

    public static function create($content, $sender, $sentAt)
    {
        $message = new Message();

        $message->content = $content;
        $message->author = $sender->id;
        $message->sent_at = $sentAt;

        $message->save();

        return $message;
    }

    /**
     * Undocumented function
     *
     * @param array $attachments
     * @param \App\Models\Message $message
     * @return array
     */
    public static function saveAttachments($attachments, $message)
    {
        $attachmentFiles = [];

        if (!$attachments || count($attachments) <= 0) {
            return $attachmentFiles;
        }

        if (count($attachments) > 10) {
            abort(422, 'Максимальное количество загружаемых файлов - 10');
        }

        foreach ($attachments as $i => $attachment) {
            $data = (object) collect(['title' => '', 'artist' => ''])->all();
            $file = FileService::create($attachment, $data);

            $model = new MessageFile();
            $model->message = $message->id;
            $model->file_id = $file->id;
            $model->file_type = $file->getMorphClass();

            $model->save();

            array_push($attachmentFiles, $file);
        }

        return $attachmentFiles;
    }

    public static function search($query, $to, $chat, $sender)
    {
        if ($to) {
            $messages = DialogService::getOrCreateDialog($sender->id, $to)->messages();
        } elseif ($chat) {
            $messages = Chat::find($chat)->userMessages;
        } else {
            $messages = collect();
            foreach ($sender->dialogsAndChatsWithMessages() as $chat) {
                $messages->push(...$chat->messages());
            }
            $messages = $messages->filter(function ($item) {
                return class_basename($item) === 'Message';
            });
        }
        return $messages->filter(function ($item) use ($query) {
            return str_contains(Crypt::decrypt($item->content), $query);
        })->values();
    }
}
