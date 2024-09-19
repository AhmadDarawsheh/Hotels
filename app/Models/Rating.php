<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'rating_reservation')
                    ->withPivot('rating_start_date', 'rating_end_date')
                    ->withTimestamps();
    }
}
