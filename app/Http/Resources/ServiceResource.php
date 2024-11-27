<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'city'   => $this->city->name ,
            'title' => $this->title,
            'clinic' => $this->room->name,
            'status' => $this->activation,
            'actions'   => "",

        ];
    }
}
