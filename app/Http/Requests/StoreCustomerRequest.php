<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required|string|max:255',
            'lead_source_id' => 'nullable|string|max:20',
            'lead_by' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'nationality_id' => 'required|exists:nationalities,id',
            'gender_id' => 'required',
            'date_of_birth' => 'nullable|date',
            'personal_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
