<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    // Relationship with the `employees` table
    public function leadBy()
    {
        return $this->belongsTo(Employee::class, 'lead_by');
    }
    
}
