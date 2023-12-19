<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetAddRequest extends FormRequest
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
            'name'=>'required',
            'type_id' =>'required',
            'sl_no' => 'nullable',
            'branch_id' =>'required',
            'specification' => 'nullable',
            'purchase_at' => 'nullable',
            'purchase_by'=> 'nullable|exists:users,id',
            'purchase_price' => 'nullable',
            'url' => 'nullable|image',
        ];
    }
}
