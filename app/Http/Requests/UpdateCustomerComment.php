<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerComment extends FormRequest
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
            'customer_id' => 'nullable|integer',
            'author_id'   => 'nullable|integer',
            'comment'     => 'string',
            'parent_id'   => 'nullable|integer',
            'level'       => 'nullable|integer'
        ];
    }
}
