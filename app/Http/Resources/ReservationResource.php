<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function getRoomDetails()
    {
        if ($this->room_reservations->isNotEmpty()) {
            return [
                'room_name'     => $this->room_reservations->first()->room->name ?? null,
                'room_type'  => $this->room_reservations->first()->room->room_type ?
                    [
                        "id" => $this->room_reservations->first()->room->room_type->id ?? null,
                        "name" => $this->room_reservations->first()->room->room_type->name  ?? null,
                    ] :
                    null,
                'apartment'     => $this->room_reservations->first()->room->apartment->name ?? null,
                'building'      => $this->room_reservations->first()->room->apartment->building->name ?? null,
            ];
        }

        if ($this->bedspace_reservations->isNotEmpty()) {
            return [
                'room_name' =>  $this->bedspace_reservations->first()->bed_space->room->name . " ( " . $this->bedspace_reservations->first()->bed_space->bed_number . " ) " ?? null,
                'room_type'  => $this->bedspace_reservations->first()->bed_space->room->room_type ?
                    [
                        "id" => $this->bedspace_reservations->first()->bed_space->room->room_type->id ?? null,
                        "name" => $this->bedspace_reservations->first()->bed_space->room->room_type->name  ?? null,
                    ] :
                    null,
                'room'           => $this->bedspace_reservations->first()->bed_space->room->name ?? null,
                'apartment'      => $this->bedspace_reservations->first()->bed_space->room->apartment->name ?? null,
                'building'       => $this->bedspace_reservations->first()->bed_space->room->apartment->building->name ?? null,
            ];
        }

        return null; // إذا لم يكن هناك حجز لغرفة أو مساحة سرير
    }


    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'is_bed_space'      => $this->bedspace_reservations->isNotEmpty() ? 1 : 0,
            'check_in_date'     => $this->check_in_date,
            'check_out_date'    => $this->check_out_date,
            'room'              => $this->getRoomDetails(),
            'customer'          => [
                'id' => $this->id,
                'first_name' => $this->customer->first_name,
                'last_name' =>  $this->customer->last_name,
                'phone_number' => $this->customer->phone_number,
                'nationality' => $this->customer->nationality,
                'gender' => $this->customer->gender,
                'id_number' => $this->customer->id_number,
                'id_photo' => $this->customer->id_photo,
                'passport_photo' => $this->customer->passport_photo,
                'personal_photo' => $this->customer->personal_photo,
                'email' => $this->customer->email,
                'date_of_birth' => $this->customer->date_of_birth,
                'note' => $this->customer->note,
            ],
            'reservation_date'  => $this->reservation_date,
            'user'  => $this->user,
            'total_amount'  => $this->total_amount,
            'status'  => $this->status,
            'actions'           => "",
        ];
    }
}
