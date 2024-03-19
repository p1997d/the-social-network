<?php

namespace App\Http\Controllers\Messanger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\Models\ChatSystemMessage;
use App\Models\ChatMessageDelete;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;
use App\Services\FileService;
use App\Events\MessagesWebSocket;

Carbon::setLocale('ru');

class ChatController extends Controller
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

    public function createChat(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|max:200',
        ]);

        $chat = new Chat();

        $chat->title = $request->title;
        $chat->author = $user->id;
        $chat->save();

        $member = new ChatMember();

        $count = ChatMember::where('user', $user->id)->count();

        $member->user = $user->id;
        $member->chat = $chat->id;
        $member->id_for_user = $count + 1;
        $member->admin = true;
        $member->joined_at = now();

        $member->save();

        $message = new ChatSystemMessage();

        $message->sender = $user->id;
        $message->recipient = null;
        $message->chat = $chat->id;
        $message->content = ($user->sex == "male" ? "создал" : "создала") . " чат «" . $chat->title . "»";
        $message->sent_at = now();

        $message->save();

        if ($request->users) {
            $usersData = [];
            foreach ($request->users as $userId) {
                $count = ChatMember::where('user', $userId)->count();

                $usersData[] = [
                    'user' => $userId,
                    'chat' => $chat->id,
                    'id_for_user' => $count + 1,
                    'admin' => false,
                    'joined_at' => now(),
                ];
            }
            ChatMember::insert($usersData);
        }

        return back();
    }

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

        $message = new ChatMessage();

        $message->sender = $sender->id;
        $message->chat = $chat->id;
        $message->content = $content;
        $message->sent_at = $sentAt;

        $attachments = [];

        if (request()->attachments) {
            foreach (request()->attachments as $i => $file) {
                $group = 'messages';

                $name =  time() . '_' . $i;

                $model = FileService::create($sender, $group, $name, $file);

                $attachments[] = $model->id;
                $attachmentsModels[] = $model->id;
            }
        }

        $message->attachments = empty($attachments) ? null : json_encode($attachments);

        $message->save();

        $attachments = $message->attachments();

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'chat', 'decryptContent', 'sentAtFormat', 'attachments');

        // event(new MessagesWebSocket($data, true));

        return $data;
    }

    public function update(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = ChatMessage::find($id);

        list($sender, $senderAvatar, $decryptContent, $content, $changedAt, $changedAtFormat) = $this->getData($request);

        $request->validate([
            'content' => 'required|max:2000',
        ]);

        $message->update([
            'content' => $content,
            'changed_at' => $changedAt
        ]);

        $attachments = $message->attachments();

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'decryptContent', 'changedAtFormat', 'attachments');

        // event(new MessagesWebSocket($data));
        return $data;
    }

    public function delete(Request $request, $id)
    {
        $type = __FUNCTION__;

        $message = ChatMessage::find($id);

        list($sender, $senderAvatar, $decryptContent, $content) = $this->getData($request);

        $data = compact('type', 'message', 'sender', 'senderAvatar', 'decryptContent');

        if ($message->sender == auth()->user()->id) {
            $deleteForAll = isset($request->deleteForAll);
            if ($deleteForAll) {
                $message->update([
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
