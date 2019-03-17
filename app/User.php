<?php

namespace App;

use App\Mail\ResetPasswordMail;
use App\Mail\VerifyAccountMail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'passwd',
        'passwd2',
        'Promt',
        'creatime',
        'answer',
        'idnumber'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    private $passwd;
    private $passwd2;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function hasActivated()
    {
        return $this->passwd != $this->passwd2;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordMail($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyAccountMail());
    }
}
