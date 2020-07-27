<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserDetails extends FormRequest
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
            'user_id'         => 'required|integer',
            'prefix'          => 'required|string',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
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
            'email_personal'  => 'required|string',
            'line_1'          => 'required|string',
            'line_2'          => 'required|string',
            'city'            => 'required|string',
            'state'           => 'required|string',
            'zip'             => 'required|string',
            'status'          => 'string'
        ];
    }
}
