<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }


    public function defaultRating()
    {
        return $this->hasOne(Rating::class)->whereNull('start_date')->whereNull('end_date');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
