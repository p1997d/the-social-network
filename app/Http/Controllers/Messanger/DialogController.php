<?php

namespace App\Http\Controllers\Messanger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dialog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Events\MessagesWebSocket;
use App\Services\DialogService;
use App\Services\FileService;
use Carbon\Carbon;

Carbon::setLocale('ru');

class DialogController extends Controller
{
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

    public function create(Request $request, $id)
    {
        $type = __FUNCTION__;

        $sender = User::find(Auth::id());
        $recipient = User::find($id);

        list($sender, $senderAvatar, $decryptContent, $content, $sentAt, $sentAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required_without:attachments|max:2000',
            'attachments' => 'required_without:content',
        ]);

        $message = new Dialog();

        $message->sender = $sender->id;
        $message->recipient = $recipient->id;
        $message->content = $content;
        $message->sent_at = $sentAt;

        $attachments = [];

        if (request()->attachments) {
            foreach (request()->attachments as $i => $file) {
                $name =  time() . '_' . $i;
                $model = FileService::create($sender, 'messages', $name, $file);

                $attachments[] = $model->id;
                $attachmentsModels[] = $model->id;
            }
        }

        $message->attachments = empty($attachments) ? null : json_encode($attachments);

        $message->save();

        $attachments = $message->attachments();

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipient', 'decryptContent', 'sentAtFormat', 'attachments');

        event(new MessagesWebSocket($data, true));

        return $data;
    }

    public function update(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Dialog::find($id);

        $recipient = User::find($message->recipient);

        list($sender, $senderAvatar, $decryptContent, $content, $changedAt, $changedAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required|max:2000',
        ]);

        $message->update([
            'content' => $content,
            'changed_at' => $changedAt
        ]);

        $attachments = $message->attachments();

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipient', 'decryptContent', 'changedAtFormat', 'attachments');

        event(new MessagesWebSocket($data));
        return $data;
    }

    public function delete(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = Dialog::find($id);

        $recipient = User::find($message->recipient);

        list($sender, $senderAvatar, $decryptContent, $content) = $this->getData($request);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'recipient', 'decryptContent');

        if ($message->sender == $sender->id) {
            $deleteForAll = isset($request->deleteForAll);
            if ($deleteForAll) {
                $message->update([
                    'delete_for_sender' => 1,
                    'delete_for_recipient' => 1
                ]);

                event(new MessagesWebSocket($data));
            } else {
                $message->update(['delete_for_sender' => 1]);
            }

        } elseif ($message->recipient == $sender->id) {
            $deleteForAll = false;
            $message->update(['delete_for_recipient' => 1]);
        } else {
            abort(404);
        }

        return $data;
    }

    public function allDelete($id)
    {
        $sender = Auth::user();
        $recipient = User::find($id);

        $messages = DialogService::getMessages($sender, $recipient)->get();
        foreach ($messages as $message) {
            if ($message->sender == $sender->id) {
                $message->update(['delete_for_sender' => 1]);
            } elseif ($message->recipient == $sender->id) {
                $message->update(['delete_for_recipient' => 1]);
            } else {
                //error
            }
        }
        return back();
    }

    public function checkRead()
    {
        $message = Dialog::find(request()->id);

        $sender = User::find($message->sender);
        $recipient = User::find($message->recipient);

        $messageIds = DialogService::getMessages($sender, $recipient)->get()->filter(function ($item) use ($message) {
            return $item->id <= $message->id && $item->viewed_at == null;
        })->pluck('id');

        Dialog::whereIn('id', $messageIds)->update([
            'viewed_at' => now()
        ]);

        return $messageIds;
    }
}
