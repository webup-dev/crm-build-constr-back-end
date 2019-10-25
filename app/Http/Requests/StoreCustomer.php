<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomer extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'type'            => 'required|string',
            'note'            => 'string',
            'organization_id' => 'required|integer',
            'email'           => 'required|email',
            'password'        => 'required|string'
        ];
    }
}
