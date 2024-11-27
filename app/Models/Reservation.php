<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function getNoticeDeadlineAttribute()
{
    return $this->check_out_date->subDays($this->notice_period_days);
}

public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reservation_type()
    {
        return $this->belongsTo(ReservationType::class, 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(ReservationStatus::class, 'status_id');
    }

    public function room_reservations()
    {
        return $this->hasMany(RoomReservation::class);
    }

    public function bedspace_reservations()
    {
        return $this->hasMany(BedSpaceReservation::class);
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, RoomReservation::class);
    }

    public function bed_spaces()
    {
        return $this->hasManyThrough(BedSpace::class, BedSpaceReservation::class);
    }

    
}
