<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'room_type' => [
                'id' => $this->room_type_id,
                'name' => $this->room_type ? $this->room_type->name : null, // Check if room_type exists
            ],
            'apartment' => [
                'id' => $this->apartment_id,
                'name' => $this->apartment ? $this->apartment->name : null, // Check if apartment exists
                'building_name' => $this->apartment->building ? $this->apartment->building->name : null, // Check if apartment exists
            ],
            'building' => [
                'id' => $this->apartment->building ?$this->apartment->building->id : null, // Check if
                'name' => $this->apartment->building ? $this->apartment->building->name : null, // Check if apartment exists
            ],
            'gender' => $this->gender,
            'has_bathroom' => $this->has_bathroom,
            'has_balcony' => $this->has_balcony,
        ];
    }
}
