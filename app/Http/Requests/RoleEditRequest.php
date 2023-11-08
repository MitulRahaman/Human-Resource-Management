<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleEditRequest extends FormRequest
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
            'id' => [
                'required',
                Rule::exists('roles', 'id'),
            ],
            'sl_no' => 'required',
            'name'=>"required|unique:roles,name,$id",
            'permissions' => 'nullable',
            'branches' => 'required|array',
            'branches.*' => 'exists:branches,id',
            'description'=> 'nullable',

        ];
    }

}
