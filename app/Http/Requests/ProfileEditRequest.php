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
            'present_address' => 'required',
            'permanent_address' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact'=> 'required',
            'relation'=> 'required',
            'emergency_contact_name2' => 'required',
            'emergency_contact2'=> 'required',
            'relation2'=> 'required',
            'institute_id'=> 'required',
            'degree_id'=> 'required',
            'major'=> 'required',
            'gpa'=>'required',
            'year'=> 'required',
            'bank_id'=> 'nullable',
            'account_name'=> 'nullable',
            'account_number'=> 'nullable',
            'branch'=> 'nullable',
            'routing_number'=> 'nullable',
            'nominee_name' => 'nullable',
            'nominee_nid' => 'nullable',
            'nominee_photo' => 'nullable|mimes:jpg,jpeg,png',
            'nominee_relation' => 'nullable',
            'nominee_phone_number' => 'nullable',
            'nominee_email' => 'nullable|email',
        ];
    }
}
