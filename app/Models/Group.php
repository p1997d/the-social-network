<?php

namespace App\Models;

use App\Services\AudioService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;
use App\Services\GroupService;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;
    protected $table = 'groups';
    protected $quarde = false;
    protected $guarded = [];

    public function members()
    {
        return User::whereIn('id', GroupUser::where('group', $this->id)->pluck('user'));
    }

    public function admins()
    {
        return GroupService::getAdmins($this);
    }

    public function members_count()
    {
        return GeneralService::getPluralize(
            GroupUser::where('group', $this->id)->count(),
            'подписчик'
        );
    }

    public function avatar()
    {
        return GeneralService::getAvatar($this);
    }

    public function avatarDefault()
    {
        return "https://ui-avatars.com/api/?name=$this->title&background=random&size=150";
    }

    public function avatarFile()
    {
        return $this->hasOne(File::class, 'id', 'avatar');
    }

    public function ifSubscribed()
    {
        return $this->members()->find(Auth::id()) !== null;
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, GroupPost::class, 'group', 'id', 'id', 'post')->where('deleted_at', null)->orderByDesc('created_at');
    }

    public function isAdmin($user)
    {
        return GroupUser::where([
            ['group', $this->id],
            ['user', $user->id],
        ])->first()->admin;
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class, GroupFile::class, 'group', 'file_id')->where([['file_type', Photo::class], ['deleted_at', null]]);
    }

    public function playlist(): MorphOne
    {
        return $this->morphOne(Playlist::class, 'playlistable');
    }

    public function audios()
    {
        return AudioService::getAudios($this->playlist);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, GroupFile::class, 'group', 'file_id')->where([['file_type', Video::class], ['deleted_at', null]]);
    }
}
