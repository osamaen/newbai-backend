<?php

namespace App\Http\Resources;

use App\Models\StudentStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'first_name'    => $this->first_name,
            'last_name' => $this->last_name,
            'father_name'   => $this->father_name,
            'mother_name'   => $this->mother_name,
            'university_id' => $this->university,
            'student_type_id'   => $this->studentType,
            'phone'         =>  $this->phone,
            'number_card'   => $this->number_card,
            'national_id'   => $this->national_id,
            'gender'    => $this->gender >= 1 ? (int)$this->gender: null,
            'province'  => $this->province,
            'avatar'  => $this->avatar,

            // 'national_card_photo1'  => $this->national_card_photo1,
            // 'national_card_photo2'  => $this->national_card_photo2,
            // 'personal_image'    => $this->personal_image,
        ];
    }
}
