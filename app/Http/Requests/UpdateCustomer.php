<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest
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
            'name'            => 'string',
            'organization_id' => 'integer',
            'type'            => 'string',
            'line_1'          => 'string|nullable',
            'line_2'          => 'string|nullable',
            'state'           => 'string|nullable',
            'zip'             => 'string|nullable'
        ];
    }
}
