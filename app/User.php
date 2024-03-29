<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'password',
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
            if (!request('user_type')) {
                if (request('os') == 'help')
                    $user->type_user = json_encode(['ADMINE']);
                else
                    $user->type_user = json_encode(['GUEST']);
            }
            else
                $user->type_user = json_encode([request('user_type')]);

        });
    }

    // Just get the role of this client (Who wanna connected)
    public function checkClientType()
    {
        $type = $this->type_user;
        return strtolower($type);
    }

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
//    fin
}
