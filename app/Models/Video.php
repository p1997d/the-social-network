<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\GeneralService;
use App\Services\FileService;
use Carbon\Carbon;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    protected $quarde = false;
    protected $guarded = [];

    public function viewsWithText() {
        return GeneralService::getPluralize($this->views, 'просмотр');
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function createdAtDiffForHumans()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function myLike()
    {
        return $this->morphOne(Like::class, 'likeable')->where('user', Auth::id());
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
