<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BedSpacesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
     public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'room' => [
                    'id' => $this->room_id,
                    'name' => $this->room ? $this->room->name : null, // Check if room_type exists
            ],
            'position_description'    => $this->position_description,
            'bed_number'    => $this->bed_number,
            'price'    => $this->price,
            'actions'   => "",
         
        ];
    }
}
