<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequisitionEditRequest extends FormRequest
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
            'id' => 'required|exists:requisition_requests,id',
            'name' => 'required',
            'specification' => 'required',
            'asset_type_id' => 'nullable',
            'remarks' => 'required',
        ];
    }
}
