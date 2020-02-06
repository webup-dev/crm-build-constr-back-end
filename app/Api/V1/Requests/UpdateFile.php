<?php

namespace App\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFile extends FormRequest
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
            "owner_object_type" => 'string',
            "owner_object_id"   => 'integer',
            "description"       => 'string',
            "filename"          => 'string',
            "owner_user_id"     => 'integer',
            "photo"             => 'file|mimes:jpeg,jpg,png,pdf,doc,docx,txt,esx,json,xml,xls,skp,dwg,dxf|max:2048'
        ];
    }
}
