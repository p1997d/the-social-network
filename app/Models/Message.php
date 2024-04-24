<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;
use App\Services\MessagesService;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $quarde = false;
    protected $guarded = [];

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function date()
    {
        return GeneralService::getDate($this->sent_at);
    }

    public function dialog()
    {
        return $this->hasOneThrough(Dialog::class, DialogMessage::class, 'message', 'id', 'id', 'dialog');
    }
    public function chat()
    {
        return $this->hasOneThrough(Chat::class, ChatMessage::class, 'message_id', 'id', 'id', 'chat');
    }

    public function attachmentsPhotos()
    {
        return $this->belongsToMany(Photo::class, MessageFile::class, 'message', 'file_id')
            ->where('file_type', Photo::class);
    }

    public function attachmentsAudios()
    {
        return $this->belongsToMany(Audio::class, MessageFile::class, 'message', 'file_id')
            ->where('file_type', Audio::class);
    }

    public function attachmentsVideos()
    {
        return $this->belongsToMany(Video::class, MessageFile::class, 'message', 'file_id')
            ->where('file_type', Video::class);
    }

    public function attachmentsFiles()
    {
        return $this->belongsToMany(File::class, MessageFile::class, 'message', 'file_id')
            ->where('file_type', File::class);
    }

    public function attachments()
    {
        $photos = $this->attachmentsPhotos;
        $audios = $this->attachmentsAudios;
        $videos = $this->attachmentsVideos;
        $files = $this->attachmentsFiles;
        return $files->push(...$photos, ...$audios, ...$videos)->sortBy('sent_at');
    }
}
