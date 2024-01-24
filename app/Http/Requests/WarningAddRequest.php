<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarningAddRequest extends FormRequest
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
            'warning_by' => 'required|exists:users,id',
            'warning_to' => 'required|exists:users,id',
            'subject' => 'required',
            'date' => 'required',
            'description' => 'nullable'
        ];
    }
}
