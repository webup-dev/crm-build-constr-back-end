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
class UpdateLeadRequest extends FormRequest
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
            "name"                     => 'string',
            "organization_id"          => 'integer',
            "due_date"                 => 'date_format:Y-m-d H:i:s|nullable',
            "anticipated_project_date" => 'date_format:Y-m-d H:i:s|nullable',
            "lead_type_id"             => 'integer',
            "lead_status_id"           => 'integer',
            "declined_reason_other"    => 'string|nullable',
            "lead_source_id"           => 'integer',
            "stage_id"                 => 'integer',
            "line_1"                   => 'string|nullable',
            "line_2"                   => 'string|nullable',
            "city"                     => 'string|nullable',
            "state"                    => 'string|nullable',
            "zip"                      => 'string|nullable',
            "requester_id"             => 'integer',
            "note"                     => 'string|nullable',
            "lead_owner_id"            => 'integer',
            "created_by_id"            => 'integer',
            "updated_by_id"            => 'integer',
            "deleted_at"               => 'date_format:Y-m-d H:i:s|nullable',
        ];
    }
}
