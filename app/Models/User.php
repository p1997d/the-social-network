<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\AudioService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\GeneralService;
use App\Services\UserService;
use App\Services\FriendsService;
use App\Services\DialogService;
use App\Services\PhotoService;
use App\Services\VideoService;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'firstname',
        'surname',
        'sex',
        'birth',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function info()
    {
        return $this->hasOne(Info::class, 'user', 'id');
    }

    public function online()
    {
        return UserService::isOnline($this->id);
    }

    public function unreadMessagesCount()
    {
        return DialogService::getUnreadMessagesCount($this->id);
    }

    public function friendForm()
    {
        return FriendsService::getFriendsForms($this);
    }

    public function scopeGetRandomUsers(Builder $query, $count)
    {
        return $query->inRandomOrder()->take($count)->get();
    }

    public function avatar()
    {
        return GeneralService::getAvatar($this);
    }

    public function avatarDefault()
    {
        return "https://ui-avatars.com/api/?name=$this->firstname+$this->surname&background=random&size=150";
    }

    public function avatarFile()
    {
        return $this->hasOneThrough(Photo::class, UserAvatar::class, 'user', 'id', 'id', 'avatar')->latest();
    }

    public function playlist(): MorphOne
    {
        return $this->morphOne(Playlist::class, 'playlistable');
    }

    public function currentPlaylist()
    {
        return $this->hasOne(CurrentPlaylist::class, 'user', 'id');
    }

    public function senderDialogs()
    {
        return $this->hasMany(Dialog::class, 'sender');
    }

    public function recipientDialogs()
    {
        return $this->hasMany(Dialog::class, 'recipient');
    }

    public function dialogs()
    {
        return $this->senderDialogs->merge($this->recipientDialogs);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_members', 'user', 'chat')->get();
    }

    public function allDialogsAndChats()
    {
        return $this->dialogs()->push(...$this->chats());
    }

    public function dialogsAndChatsWithMessages()
    {
        return $this->allDialogsAndChats()->filter(function ($item) {
            return $item->messages()->count() > 0;
        });
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, UserPost::class, 'user', 'id', 'id', 'post')->where('deleted_at', null)->orderByDesc('created_at');
    }

    public function postsWithDeleted()
    {
        return $this->hasManyThrough(Post::class, UserPost::class, 'user', 'id', 'id', 'post')->orderByDesc('created_at');
    }

    public function groups()
    {
        return $this->hasManyThrough(Group::class, GroupUser::class, 'user', 'id', 'id', 'group');
    }

    public function groupsWhereAdmin()
    {
        $groups = GroupUser::where([
            ['user', $this->id],
            ['admin', 1],
        ])->pluck('group')->toArray();

        return Group::where('author', $this->id)->orWhereIn('id', $groups)->get();
    }

    public function photos()
    {
        return PhotoService::getPhotos($this);
    }

    public function audios()
    {
        return AudioService::getAudios($this->playlist);
    }

    public function allInfo()
    {
        return UserService::getInfo($this);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, UserFile::class, 'user', 'file_id')->where([['file_type', Video::class], ['deleted_at', null]])->orderByDesc('created_at');

    }

    public function birthDate()
    {
        return Carbon::parse($this->birth);
    }
}
