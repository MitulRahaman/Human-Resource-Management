<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketEditRequest extends FormRequest
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
            'id' => 'required|exists:tickets,id',
            'subject' => 'required',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required',
            'deadline' => 'required',
            'description' => 'nullable',
        ];
    }
}
