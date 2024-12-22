<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
     public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name  . " " . $this->last_name,
            'phone_number' => $this->phone_number,
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'id_number' => $this->id_number,
            'id_photo' => $this->id_photo,
            'passport_photo' => $this->passport_photo,
            'personal_photo' => $this->personal_photo,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            
        ];
    }
}
