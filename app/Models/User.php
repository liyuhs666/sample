<?php

namespace App\Models;

//   照亮     \   通知      \  消息通知相关
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//                  基础              授权相关功能


class User extends Authenticatable
{
    use Notifiable;

    // protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return  "http://www.gravatar.com/avatar/$hash?s=$size";
    }

}
