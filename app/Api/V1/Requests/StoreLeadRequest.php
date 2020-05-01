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
class StoreLeadRequest extends FormRequest
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
            "name"                     => 'required|string',
            "organization_id"          => 'required|integer',
            "due_date"                 => 'date_format:Y-m-d H:i:s|nullable',
            "anticipated_project_date" => 'date_format:Y-m-d H:i:s|nullable',
            "lead_type_id"             => 'required|integer',
            "lead_status_id"           => 'required|integer',
            "declined_reason_other"    => 'string|nullable',
            "lead_source_id"           => 'required|integer',
            "stage_id"                 => 'required|integer',
            "line_1"                   => 'string|nullable',
            "line_2"                   => 'string|nullable',
            "city"                     => 'string|nullable',
            "state"                    => 'string|nullable',
            "zip"                      => 'string|nullable',
            "requester_id"             => 'required|integer',
            "note"                     => 'string|nullable',
            "lead_owner_id"            => 'required|integer',
            "created_by_id"            => 'required|integer',
            "updated_by_id"            => 'integer|nullable',
            "deleted_at"               => 'date_format:Y-m-d H:i:s|nullable',
        ];
    }
}
