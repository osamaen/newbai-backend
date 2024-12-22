<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationTypeResource extends JsonResource
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
            'name'    => $this->name,
            'minimum_stay_days'    => $this->minimum_stay_days,
            'actions'   => "",
         
        ];
    }
}
