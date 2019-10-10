<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserProfile extends FormRequest
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
            'user_id'          => 'required|integer',
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'title'            => 'string',
            'department_id'    => 'required|integer',
            'phone_home'       => 'string',
            'phone_work'       => 'string',
            'phone_extension'  => 'string',
            'phone_mob'        => 'string',
            'email_personal'   => 'email',
            'email_work'       => 'email',
            'address_line_1'   => 'required|string',
            'address_line_2'   => 'string',
            'city'             => 'required|string',
            'state'            => 'required|string',
            'zip'              => 'required|string',
            'status'           => 'required|string',
            'start_date'       => 'nullable|date_format:"Y-m-d"',
            'termination_date' => 'nullable|date_format:"Y-m-d"',
            'deleted_at'       => 'nullable|date_format:"Y-m-d"'
        ];
    }
}
