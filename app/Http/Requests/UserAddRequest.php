<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|unique:users,employee_id',
            'personal_email' => 'required|email|unique:basic_info,personal_email',
            'preferred_email' => 'required|email|unique:basic_info,preferred_email',
            'preferred_email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone_number',
            'photo' => 'image',
        ];
    }
}
