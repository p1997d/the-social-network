<?php

namespace App\Services;

use App\Models\Dialog;
use App\Models\DialogMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DialogService
{
    /**
     * Получает список сообщений диалога
     *
     * @param \App\Models\Dialog $dialog
     * @return \App\Models\Message
     */
    public static function getMessages($dialog)
    {
        return Message::whereIn('id', $dialog->messages()->pluck('id'));
    }

    /**
     *  Получает количество непрочитанных сообщений
     *
     * @param int $id
     * @return int|null
     */
    public static function getUnreadMessagesCount($id)
    {
        if (Auth::guest()) {
            return null;
        }

        return Dialog::find($id)->messages()->where('author', '!=', Auth::id())->whereNull('viewed_at')->count();
    }

    /**
     * Получает диалог
     *
     * @param int $sender_id
     * @param int $recipient_id
     * @return \App\Models\Dialog
     */
    public static function getOrCreateDialog($sender_id, $recipient_id)
    {
        $dialog = Dialog::where([
            ['sender', $sender_id],
            ['recipient', $recipient_id],
        ])->orWhere([
                    ['sender', $recipient_id],
                    ['recipient', $sender_id],
                ]);

        if ($dialog->doesntExist()) {
            $dialog = new Dialog();
            $dialog->sender = $sender_id;
            $dialog->recipient = $recipient_id;
            $dialog->save();
        } else {
            $dialog = $dialog->first();
        }

        return $dialog;
    }

    /**
     * Создает сообщение
     *
     * @param string $content
     * @param \App\Models\User $sender
     * @param mixed $sentAt
     * @param \App\Models\Dialog $dialog
     * @return \App\Models\Message
     */
    public static function createMessage($content, $sender, $sentAt, $dialog)
    {
        $message = MessagesService::create($content, $sender, $sentAt);

        $dm = new DialogMessage();
        $dm->dialog = $dialog->id;
        $dm->message = $message->id;
        $dm->save();

        return $message;
    }

    /**
     * Получает данные о диалоге
     *
     * @param \App\Models\User $to
     * @param \App\Models\User $sender
     * @param string $title
     * @return array|\Illuminate\Contracts\Support\Renderable
     */
    public static function getDialog($to, $sender, $title)
    {
        $recipient = User::find($to);

        if (!$recipient) {
            return view('main.info', ['info' => 'Страница удалена либо ещё не создана.']);
        }

        $dialog = DialogService::getOrCreateDialog($sender->id, $recipient->id);

        $messages = DialogService::getMessages($dialog)->orderBy('created_at', 'DESC')->paginate(25)->appends(['to' => $to])->onEachSide(1);

        return compact('messages', 'recipient', 'title');
    }
}
