<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friends extends Model
{
    use HasFactory;

    protected $table = 'friends';
    protected $quarde = false;
    protected $guarded = [];

    public static function listOutgoing()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user1', $auth_user_id], ['status', 0]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public static function listIncoming()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user2', $auth_user_id], ['status', 0]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }
}
