<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $quarde = false;
    protected $guarded = [];

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function attachmentsPhotos()
    {
        return $this->belongsToMany(Photo::class, PostFile::class, 'post', 'file_id')
            ->where('file_type', Photo::class);
    }

    public function attachmentsAudios()
    {
        return $this->belongsToMany(Audio::class, PostFile::class, 'post', 'file_id')
            ->where('file_type', Audio::class);
    }

    public function attachmentsVideos()
    {
        return $this->belongsToMany(Video::class, PostFile::class, 'post', 'file_id')
            ->where('file_type', Video::class);
    }

    public function attachmentsFiles()
    {
        return $this->belongsToMany(File::class, PostFile::class, 'post', 'file_id')
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

    public function group()
    {
        return $this->hasOneThrough(Group::class, GroupPost::class, 'post', 'id', 'id', 'group');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function myLike()
    {
        return $this->morphOne(Like::class, 'likeable')->where('user', Auth::id());
    }

    public function createdAtDiffForHumans()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('created_at');
    }
}
