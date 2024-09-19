<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = ['name', 'address', 'user_id'];

    public function employees()
    {
        return $this->belongsToMany(User::class, 'hotel_user')->wherePivot('role', 'employee');
    }

    public function creators()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'creator');
    }


    public function rooms() {
        return $this->hasMany(Room::class);
    }
}
