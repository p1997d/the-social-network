<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphOne;

Carbon::setLocale('ru');

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

    public function messages_sender()
    {
        return $this->hasMany(Dialog::class, 'sender', 'id');
    }

    public function messages_recipient()
    {
        return $this->hasMany(Dialog::class, 'recipient', 'id');
    }

    public function online()
    {
        return UserService::isOnline($this->id);
    }

    public function lastMessage()
    {
        return DialogService::getMessages(Auth::user(), $this)->get()->first();
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
        return $this->hasOneThrough(File::class, Info::class, 'user', 'id', 'id', 'avatar');
    }

    public function playlist(): MorphOne
    {
        return $this->morphOne(Playlist::class, 'playlistable');
    }

    public function currentPlaylist()
    {
        return $this->hasOne(CurrentPlaylist::class, 'user', 'id');
    }
}
