<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Message;
use App\Models\ChatMessageDelete;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;
use App\Services\FileService;
use App\Services\ChatService;
use App\Services\MessagesService;
use App\Events\MessagesWebSocket;

class ChatController extends Controller
{
    /**
     * Получить общие данные для всех страниц.
     *
     * @return array
     */
    private function getData($request)
    {
        $sender = User::find(Auth::id());
        $senderAvatar = $sender->avatar();

        $decryptContent = $request->content;
        $content = Crypt::encrypt($decryptContent);

        $time = now();
        $timeFormat = Carbon::parse($time)->diffForHumans();

        return array($sender, $senderAvatar, $decryptContent, $content, $time, $timeFormat);
    }

    /**
     * Создает новый чат
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createChat(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'title' => 'required|max:200',
        ]);

        $chat = ChatService::create($request->title, $user);

        $member = ChatService::addMember($user, $chat);

        $content = ($user->sex == "male" ? "создал" : "создала") . " чат «" . $chat->title . "»";
        $message = ChatService::createSystemMessage($user, $chat, $content);

        if ($request->users) {
            ChatService::addMembers($request->users, $chat);
        }

        return back();
    }

    /**
     * Отправляет сообщение в чат
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function create(Request $request, $id)
    {
        $type = __FUNCTION__;

        $sender = User::find(Auth::id());
        $chat = Chat::find($id);

        list($sender, $senderAvatar, $decryptContent, $content, $sentAt, $sentAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required_without:attachments|max:2000',
            'attachments' => 'required_without:content',
        ]);

        $message = ChatService::createMessage($content, $sender, $sentAt, $chat);

        $attachments = MessagesService::saveAttachments(request()->attachments, $message);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'chat', 'decryptContent', 'sentAtFormat', 'attachments');

        // event(new MessagesWebSocket($data, true));

        return $data;
    }

    /**
     * Редактирует сообщение в чате
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Message::find($id);

        list($sender, $senderAvatar, $decryptContent, $content, $changedAt, $changedAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required|max:2000',
        ]);

        $message->update([
            'content' => $content,
            'changed_at' => $changedAt
        ]);

        // $attachments = $message->attachments();
        $attachments = [];

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'decryptContent', 'changedAtFormat', 'attachments');

        // event(new MessagesWebSocket($data));
        return $data;
    }

    /**
     * Удаляет сообщение из чата
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Message::find($id);

        list($sender, $senderAvatar, $decryptContent, $content) = $this->getData($request);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'decryptContent');

        if ($message->author == auth()->user()->id) {
            $deleteForAll = isset ($request->deleteForAll);
            if ($deleteForAll) {
                $cm = ChatMessage::where([
                    ['message_id', $message->id],
                    ['message_type', Message::class],
                    ['chat', $message->chat->id],
                ])->first();


                $cm->update([
                    'delete_for_all' => 1,
                ]);

                // event(new MessagesWebSocket($data));
            } else {
                $model = new ChatMessageDelete();

                $model->user = auth()->user()->id;
                $model->message = $message->id;
                $model->deleted_at = now();

                $model->save();
            }

        } else {
            $deleteForAll = false;

            $model = new ChatMessageDelete();

            $model->user = auth()->user()->id;
            $model->message = $message->id;
            $model->deleted_at = now();

            $model->save();
        }

        return $data;
    }
}
