<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedspaceReservation extends Model
{
    use HasFactory;
    protected $table='bedspace_reservations';
    protected $guarded= [];
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function bed_space()
    {
        return $this->belongsTo(BedSpace::class,'bed_space_id');
    }
    
}
