<?php

namespace App\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation of storing of a new requester
 *
 * @category RequestValidation
 * @package  Requester
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     RequestValidation
 */
class UpdateRequesterRequest extends FormRequest
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
            "organization_id" => 'required|integer',
            "prefix" => 'string',
            "first_name" => 'string',
            "last_name" => 'string',
            "suffix" => 'string',
            "email_work" => 'email',
            "email_personal" => 'email',
            "line_1" => 'string',
            "line_2" => 'string',
            "city" => 'string',
            "state" => 'string',
            "zip" => 'string',
            "phone_home" => 'string',
            "phone_work" => 'string',
            "phone_extension" => 'string',
            "phone_mob1" => 'string',
            "phone_mob2" => 'string',
            "phone_fax" => 'string',
            "website" => 'string',
            "other_source" => 'string',
            "note" => 'string',
            "created_by_id" => 'required|integer',
            "updated_by_id" => 'required|integer'
        ];
    }
}
