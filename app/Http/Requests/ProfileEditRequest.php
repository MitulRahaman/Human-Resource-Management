<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
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
        $id = $this->route('id');
        $this->merge(['id' => $id]);
        return [
            'id' =>'required',
            'father_name'=> 'required',
            'mother_name' => 'required',
            'nid'=> 'required',
            'birth_certificate'=> 'nullable',
            'passport_no'=> 'nullable',
            'gender'=> 'required',
            'religion'=> 'required',
            'blood_group'=> 'required',
            'dob'=> 'required',
            'marital_status'=> 'required',
            'no_of_children'=> 'nullable',
            'emergency_contact_name' => 'nullable',
            'emergency_contact'=> 'required',
            'relation'=> 'required',
            'institute_id'=> 'required',
            'degree_id'=> 'required',
            'major'=> 'required',
            'year'=> 'required',
            'bank_id'=> 'required',
            'account_name'=> 'required',
            'account_number'=> 'required',
            'branch'=> 'required',
            'routing_number'=> 'nullable',
            'nominee_name' => 'required',
            'nominee_nid' => 'required',
            'nominee_photo' => 'required|mimes:jpg,jpeg,png',
            'nominee_relation' => 'required',
            'nominee_phone_number' => 'nullable',
            'nominee_email' => 'nullable|email',
        ];
    }
}
