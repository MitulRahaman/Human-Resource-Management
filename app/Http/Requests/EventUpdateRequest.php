<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'title' => 'required',
            'branchId' => 'required',
            'departmentId' => 'required',
            'participantId' => 'required',
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'nullable|date_format:d/m/Y',
            'photo' => 'nullable|max:10240',
        ];
    }
}
