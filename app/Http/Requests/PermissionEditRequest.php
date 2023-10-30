<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionEditRequest extends FormRequest
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
                    Rule::exists('permissions', 'id'),
                ],
              'name'=>"required|unique:permissions,name,$id",
              'description'=> 'nullable',
            ];
    }
    public function messages()
    {
        return [
            'id.exists' => 'The specified ID does not exist in menus table.',
        ];
    }
}
