<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;
use App\Models\DialogMessage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Events\MessagesWebSocket;

use App\Services\DialogService;
use App\Services\MessagesService;
use Carbon\Carbon;

class DialogController extends Controller
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
     * Отправляет сообщение пользователю
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function create(Request $request, $id)
    {
        $type = __FUNCTION__;

        $sender = User::find(Auth::id());

        $recipient = User::find($id);
        $recipients = [$recipient->id];

        list($sender, $senderAvatar, $decryptContent, $content, $sentAt, $sentAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required_without:attachments|max:2000',
            'attachments' => 'required_without:content',
        ]);

        $dialog = DialogService::getOrCreateDialog($sender->id, $recipient->id);

        $message = DialogService::createMessage($content, $sender, $sentAt, $dialog);

        $attachments = MessagesService::saveAttachments(request()->attachments, $message);

        $subtitle = "$sender->firstname $sender->surname";
        $link = route('messages', ['to' => $sender->id]);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipients','decryptContent', 'sentAtFormat', 'attachments', 'subtitle', 'link');

        event(new MessagesWebSocket($data, true));

        return $data;
    }

    /**
     * Редактирует сообщение в диалоге
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Message::find($id);

        $recipient = $message->dialog->interlocutor;
        $recipients = [$recipient->id];

        list($sender, $senderAvatar, $decryptContent, $content, $changedAt, $changedAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required|max:2000',
        ]);

        $message->update([
            'content' => $content,
            'changed_at' => $changedAt
        ]);

        $attachments = $message->attachments();

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipients', 'decryptContent', 'changedAtFormat', 'attachments');

        event(new MessagesWebSocket($data));
        return $data;
    }

    /**
     * Удаляет сообщение пользователя
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Message::find($id);

        $recipient = $message->dialog->interlocutor;
        $recipients = [$recipient->id];

        list($sender, $senderAvatar, $decryptContent, $content) = $this->getData($request);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipients', 'decryptContent');

        $dm = DialogMessage::where([
            ['message', $message->id],
            ['dialog', $message->dialog->id],
        ])->first();

        $deleteForAll = false;

        if ($message->author === auth()->user()->id) {
            $deleteForAll = isset($request->deleteForAll);
        }

        if ($deleteForAll) {
            $dm->update([
                'delete_for_sender' => 1,
                'delete_for_recipient' => 1
            ]);

            event(new MessagesWebSocket($data));
        } else {
            if (auth()->user()->id == $message->dialog->sender) {
                $dm->update(['delete_for_sender' => 1]);
            } elseif (auth()->user()->id == $message->dialog->recipient) {
                $dm->update(['delete_for_recipient' => 1]);
            } else {
                abort(403);
            }
        }

        return $data;
    }

    /**
     * Очищает диалог
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allDelete($id)
    {
        $sender = User::find(Auth::id());
        $recipient = User::find($id);

        $dialog = DialogService::getOrCreateDialog($sender->id, $recipient->id);

        $dm = DialogMessage::where('dialog', $dialog->id)->whereIn('message', $dialog->messages()->pluck('id'));

        if ($dialog->sender == auth()->user()->id) {
            $dm->update(['delete_for_sender' => 1]);
        } elseif ($dialog->recipient == auth()->user()->id) {
            $dm->update(['delete_for_recipient' => 1]);
        }

        return redirect()->route('messages');
    }
}
