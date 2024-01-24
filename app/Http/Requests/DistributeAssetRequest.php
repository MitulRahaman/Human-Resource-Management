<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistributeAssetRequest extends FormRequest
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
        $this->merge(['user_id' => $id]);
        return [
            'user_id' => 'required|exists:users,id',
            'assets' => 'required|array',
            'assets.*' => 'exists:assets,id',
        ];
    }
}
