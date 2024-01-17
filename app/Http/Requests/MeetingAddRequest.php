<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingAddRequest extends FormRequest
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
            'agenda' => 'required',
            'date' => 'required',
            'place' => 'required|exists:meeting_places,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'url' => 'nullable',
            'description' => 'required',
            'participants' => 'required|array',
            'participants.*' => 'exists:users,id',
            'timezone' => 'nullable'
        ];
    }
}
