<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Classes\FriendsForm;
use App\Classes\AllInfo;

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

    public function scopeGetRandomUsers(Builder $query, $count)
    {
        return $query->inRandomOrder()->take($count)->get();
    }

    public function getAvatar()
    {
        $user = clone $this;

        if (optional($user->info)->avatar && Storage::exists("public/avatars/" . $user->info->avatar)) {
            return Storage::url("public/avatars/" . $user->info->avatar);
        }

        return "https://ui-avatars.com/api/?name=$user->firstname+$user->surname&background=random&size=150";
    }

    public function isOnline()
    {
        $status = Cache::get('online-' . $this->id);
        $wasOnline = "был в сети " . Carbon::parse(Cache::get('wasOnline-' . $this->id))->diffForHumans();
        $mobile = Cache::get('onlineMobile-' . $this->id);
        $online = $status ? 'online' : $wasOnline;

        return compact('mobile', 'online', 'status');
    }

    public function messages_sender()
    {
        return $this->hasMany(Dialog::class, 'sender', 'id');
    }

    public function messages_recipient()
    {
        return $this->hasMany(Dialog::class, 'recipient', 'id');
    }

    public function getLastMessage()
    {
        $senderMessages = $this->messages_sender;
        $recipientMessages = $this->messages_recipient;

        $mergedMessages = $senderMessages->merge($recipientMessages)->filter(function ($item) {
            return ($item->sender == Auth::id() || $item->recipient == Auth::id()) && $item->delete_for_sender == 0;
        })->sortBy('sent_at')->last();

        return $mergedMessages;
    }

    public function getMessageCount()
    {
        if (Auth::guest()) {
            return null;
        }

        $messages = $this->messages_sender->where('recipient', Auth::id())
            ->reject(function ($item) {
                return $item->delete_for_recipient != 0;
            })
            ->reject(function ($item) {
                return $item->viewed_at != null;
            });

        return $messages->count();
    }

    public function getFriendsModels()
    {
        if (Auth::guest()) {
            return null;
        }

        $auth_user_id = Auth::id();
        $user_profile_id = $this->id;

        $friend = Friends::where([['user1', $user_profile_id], ['user2', $auth_user_id]])
            ->orWhere([['user1', $auth_user_id], ['user2', $user_profile_id]])->get();

        return $friend;
    }

    public function getFriendsForms()
    {
        $forms = [];
        if (
            $this->getFriendsModels()->filter(function ($item) {
                return $item->status == 0 && $item->user2 == $this->id;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Отменить заявку', 'bi-ban', route('friends.canceladdfriend', ['user' => $this->id]), 'btn-secondary');
        } elseif (
            $this->getFriendsModels()->filter(function ($item) {
                return $item->status == 0 && $item->user1 == $this->id;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Добавить в друзья', 'bi-person-fill-add', route('friends.approveaddfriend', ['user' => $this->id]), 'btn-primary');
            $forms[] = new FriendsForm('Отклонить заявку', 'bi-ban', route('friends.rejectaddfriend', ['user' => $this->id]), 'btn-secondary');
        } elseif (
            $this->getFriendsModels()->filter(function ($item) {
                return $item->status == 1;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Убрать из друзей', 'bi-ban', route('friends.unfriend', ['user' => $this->id]), 'btn-secondary');
        } else {
            $forms[] = new FriendsForm('Добавить в друзья', 'bi-person-fill-add', route('friends.addfriend', ['user' => $this->id]), 'btn-primary');
        }

        return $forms;
    }

    public function getInfo()
    {
        $info = [];
        $info[] = new AllInfo(
            'День рождения',
            'bi-gift',
            Carbon::parse($this->birth)->isoFormat('D MMMM YYYY') . ' (' . Carbon::parse($this->birth)->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]) . ')'
        );

        if (!$this->info) {
            return $info;
        }

        $location = $this->info->getLocation();
        if ($location) {
            $info[] = new AllInfo('Местоположение', 'bi-geo-alt', end($location)['name']);
        }

        $education = $this->info->getEducation();
        if ($education) {
            $info[] = new AllInfo('Образование', 'bi-mortarboard', $education);
        }

        $familyStatus = $this->info->getFamilyStatus();
        if ($familyStatus) {
            $info[] = new AllInfo('Семейное положение', 'bi-heart', $familyStatus);
        }

        return $info;
    }

    public function listFriends()
    {
        $friends = Friends::where([['user1', $this->id], ['status', 1]])
            ->orWhere([['user2', $this->id], ['status', 1]])
            ->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$this->id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public function listOnlineFriends()
    {
        $friends = $this->listFriends()->get();

        $userIds = $friends->filter(function ($friend) {
            return $friend->isOnline();
        })->pluck('id');

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public function listCommonFriends()
    {
        $auth_user_id = Auth::id();

        if ($auth_user_id == $this->id) {
            return null;
        }

        $friends1 = Friends::where([['user1', $this->id], ['status', 1]])
            ->orWhere([['user2', $this->id], ['status', 1]])
            ->get();

        $friends2 = Friends::where([['user1', $auth_user_id], ['status', 1]])
            ->orWhere([['user2', $auth_user_id], ['status', 1]])
            ->get();

        $userIds1 = $friends1->pluck('user1')
            ->merge($friends1->pluck('user2'))
            ->diff([$this->id])
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $userIds2 = $friends2->pluck('user1')
            ->merge($friends2->pluck('user2'))
            ->diff([$this->id])
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $userIds = array_intersect($userIds1, $userIds2);

        $users = User::whereIn('id', $userIds);

        return $users;
    }
}
