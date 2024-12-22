<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $guarded= [];
    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
