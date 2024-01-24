<?php

namespace App\Http\Requests;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;

class RoleAddRequest extends FormRequest
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
            'sl_no' => 'required',
            'name' => 'required|unique:roles,name',
            'permissions'=> 'nullable',
            'branches' => 'required|array',
            'branches.*' => 'exists:branches,id',
            'description' => 'nullable',
        ];
    }
}
