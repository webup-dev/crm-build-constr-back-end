<?php

namespace App\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation of storing of a new Workflow
 *
 * @category RequestValidation
 * @package  Stage
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     RequestValidation
 */
class StoreWorkflowRequest extends FormRequest
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
            "name"            => 'required|string',
            "organization_id" => 'required|integer',
            "workflow_type"   => 'required|string',
            "description"     => 'nullable|string',
        ];
    }
}
