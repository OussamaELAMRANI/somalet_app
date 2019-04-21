<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

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

    /**
     * @Overried
     * Crypt the password before save !
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->password = bcrypt($user->password);
        });
    }

    // Just get the role of this client (Who wanna connected)
    public function checkClientType()
    {
        $type = $this->type_user;
        return strtolower($type);
    }
}
