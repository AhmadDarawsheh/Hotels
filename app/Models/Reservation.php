<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;


    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id')->where('type', 'customer');
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class, 'rating_reservation')
            ->withPivot('rating_start_date', 'rating_end_date')
            ->withTimestamps();
    }
}
