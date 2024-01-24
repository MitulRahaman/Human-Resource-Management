<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetEditRequest extends FormRequest
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
            'id' => 'required|exists:assets,id',
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
