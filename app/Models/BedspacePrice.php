<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedspacePrice extends Model
{
    use HasFactory;

    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    public function bedspaceReservations()
    {
        return $this->hasMany(BedspaceReservation::class, 'bed_space_id');
    }

}
