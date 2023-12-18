<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveApplyUpdateRequest extends FormRequest
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
            'leaveTypeId' => 'required',
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y',
            'reason' => 'required',
            'totalLeave' => 'max:90',
        ];
    }
}
