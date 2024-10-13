<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }


    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
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


    public function hotels($role = null)
    {
        return $this->belongsToMany(Hotel::class, 'hotel_user')->withPivot('role', $role);
    }

    public function createdHotels()
    {
        return $this->hotels('creator');
    }

    public function isEmployee()
    {
        return $this->type === 'employee';
    }

    public function isCustomer()
    {
        return $this->type === 'customer';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
}
