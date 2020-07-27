<?php

namespace App\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFile extends FormRequest
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
            "owner_object_type" => 'required|string',
            "owner_object_id"   => 'required|integer',
            "description"       => 'string',
            "filename"          => 'required|string',
            "owner_user_id"     => 'required|integer',
            "photo"             => 'file|mimes:jpeg,jpg,png,tiff,pdf,doc,docx,txt,esx,zip,json,xml,xls,skp,dwg,dxf|max:9048'
        ];
    }
}
