<?php

namespace App\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation of updating of a new LeadType
 *
 * @category RequestValidation
 * @package  LeadTypes
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     RequestValidation
 */
class UpdateLeadType extends FormRequest
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
            "name"            => 'string',
            "organization_id" => 'integer'
        ];
    }
}
