<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetails extends FormRequest
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
            'user_id'         => 'integer',
            'prefix'          => 'string',
            'first_name'      => 'string',
            'last_name'       => 'string',
            'suffix'          => 'string',
            'work_title'      => 'string',
            'work_department' => 'string',
            'work_role'       => 'string',
            'phone_home'      => 'string',
            'phone_work'      => 'string',
            'phone_extension' => 'string',
            'phone_mob'       => 'string',
            'phone_fax'       => 'string',
            'email_work'      => 'string',
            'email_personal'  => 'string',
            'line_1'          => 'string',
            'line_2'          => 'string',
            'city'            => 'string',
            'state'           => 'string',
            'zip'             => 'string',
            'status'          => 'string'
        ];
    }
}
