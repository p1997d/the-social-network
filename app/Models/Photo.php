<?php

namespace App\Models;

use App\Services\GeneralService;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';
    protected $quarde = false;
    protected $guarded = [];

    public function date()
    {
        return GeneralService::getDate($this->created_at);
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
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

    public function createdTheSameDay($photo)
    {
        return Carbon::parse($this->created_at)->isSameDay(Carbon::parse($photo->created_at));
    }

    public function size()
    {
        return FileService::getSize($this->size);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('created_at');
    }
}
