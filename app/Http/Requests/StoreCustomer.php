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
            'name'                   => 'required|string',
            'organization_id'        => 'required|integer',
            'type'                   => 'required|string',
            'city'                   => 'required|string',
            'line_1'                 => 'string|nullable',
            'line_2'                 => 'string|nullable',
            'state'                  => 'string|nullable',
            'zip'                    => 'string|nullable',
            'customer_owner_user_id' => 'required|integer'
        ];
    }
}
