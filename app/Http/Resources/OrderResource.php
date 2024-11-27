<?php

namespace App\Http\Resources;

use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student = $this->students;




        if($this->status == 9){


            $status =  OrderStatus::find(2) ;



        } elseif ($this->status == 10){


            $status =  OrderStatus::find(6) ;

            

        }else{

            $status =  $this->orderStatus  ;
        }




        // "status"=> $this->status == 9 ? OrderStatus::find(2) : $this->orderStatus ,





        return [
                "id"=> $this->id,
                "status"=> $status ,
                "submit_date"=> $this->submit_date,
                "status_change_date"=> $this->status_change_date,
                "status_change_reason"=> $this->status_change_reason,
                'note'              => $this->note,
                // "created_at"=> "2023-05-22T07=>39=>06.000000Z",
                // "updated_at"=> "2023-05-23T10=>07=>28.000000Z",
                "order_number"  => $this->order_number ?? null,
                "documents" => $this->attaches->pluck('id'),
                "student"=> [
                    "id"=>  $student->id,
                    "first_name"=>  $student->first_name,
                    "father_name"=> $student->father_name,
                    "last_name"=>   $student->last_name,
                    "mother_name"=> $student->mother_name,
                    "national_id"=> $student->national_id,
                    "phone"=>   $student->phone,
                    "province_id"=> $student->province,
                    "university_id"=>   $student->university,
                    "collage_id"=>  $student->collage,
                    "room_id"=> [
                        "id"    => $student->room_id,
                        "name"    => $student->room ? $student->room->name : null,
                        "unit_name"    => $student->room ? $student->room->unit->name: null,
                        "unit_id"    =>$student->room ? $student->room->unit->id : null,
                    ],
                    "student_type_id"=> $student->studentType,
                    'specialization'    => $student->specialization,
                    'number_card'   => $student->number_card,
                    'national_card_photo1'  => $student->national_card_photo1,
                    'national_card_photo2'  => $student->national_card_photo2,
                    'passport_photo1'   => $student->passport_photo1,
                    'passport_photo2'   => $student->passport_photo2,
                    'university_number' => $student->university_number,
                    'ban_date'  => $student->ban_date,
                    'ban_reason'    => $student->ban_reason,
                    'updated_at'    => $student->updated_at,
                    'year'  => $student->year,
                    'gender'    => $student->gender,
                    'personal_image'    => $student->personal_image,
                    'inhabitant'        => $student->inhabitant,
                ],

        ];
    }
}
