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

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if (array_key_exists('organizationName',$data)) {
            if ($data['organizationName']) {
                if (is_numeric($data['organizationName'])) {
                    $data['organization_id'] = $data['organizationName'];
                } else {
                    $data['organization_name'] = $data['organizationName'];
                }
            } else {
                $data['organization_id'] = null;
            }
        } else {
            $data['organization_id'] = null;
        }
        return $data;
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
            'branchId' => 'required',
            'roleId' => 'required',
            'full_name' => 'required',
            'nick_name' => 'required',
            'joining_date' => 'required',
            'personal_email' => 'required|email|unique:basic_info,personal_email',
            'preferred_email' => 'required|email|unique:basic_info,preferred_email',
            'phone' => 'required|unique:users,phone_number',
            'photo' => 'image',
            'organizationName' => 'nullable',
            'organization_id' => 'nullable|exists:organizations,id',
            'organization_name' => 'nullable|unique:organizations,name',
            'line_manager' => 'nullable|exists:users,id',
        ];
    }
}
