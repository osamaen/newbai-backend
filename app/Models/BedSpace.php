<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedSpace extends Model
{
    use HasFactory;
    protected $guarded= [];


    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function bedspace_reservations()
    {
        return $this->hasMany(BedSpaceReservation::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, BedSpaceReservation::class);
    }
}
