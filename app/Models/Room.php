<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function room_type()
{
    return $this->belongsTo(RoomType::class);
}

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
    public function room_reservations()
    {
        return $this->hasMany(RoomReservation::class);
    }


    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
    
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, RoomReservation::class);
    }
    public function bed_spaces()
    {
        return $this->hasMany(BedSpace::class);
    }
}
