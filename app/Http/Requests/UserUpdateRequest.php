<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'preferred_email' => 'required', 'email', Rule::unique('users', 'email')->ignore($this->id),
            'phone' => 'required', Rule::unique('users', 'phone_number')->ignore($this->id),
            'photo' => 'image',
            'preferred_email' => 'required', 'email', Rule::unique('basic_info', 'preferred_email'),
            'personal_email' => 'required', 'email', Rule::unique('basic_info', 'personal_email'),

        ];
    }
}
