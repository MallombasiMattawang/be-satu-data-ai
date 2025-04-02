<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles; //import Traits HasRoles spatie
use Tymon\JWTAuth\Contracts\JWTSubject; // <-- import JWTSubject
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject // <-- implementasikan JWT
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; //implementasi HasRoles

    /**
     * guard_name
     * otentikasi bebrbasis Rest API
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * getPermissionArray
     * untuk mendapat list permission berdasarkan user yang login
     * @return void
     */
    public function getPermissionArray()
    {
        return $this->getAllPermissions()->mapWithKeys(function ($pr) {
            return [$pr['name'] => true];
        });
    }

    // tambahkan 2 method lagi dibawah ini untuk identifikasi dan customClaim JWT
    /**
     * getJWTIdentifier
     * 
     * @return void
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * getJWTCustomClaims
     *
     * @return void
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}