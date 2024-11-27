<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return   [
            'id'    => $this->id,
            'name'  =>  $this->name,
            'building_id'  =>  [
                'id' => $this->building->id,
                'name' => $this->building->name
            ],
            'floor_number'  =>  $this->floor_number,
            'total_rooms'  =>  $this->total_rooms,
        ];
    }
}
