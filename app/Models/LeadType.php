<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model App\Models\LeadType
 *
 * @category Model
 * @package LeadSourceCategoriess
 * @author Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link Model
 * @property int                             $id
 * @property int                             $organization_id
 * @property string                          $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organization   $organization
 * @method static bool|null forceDelete()
 * @method static Builder|\App\Models\LeadType newModelQuery()
 * @method static Builder|\App\Models\LeadType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadType onlyTrashed()
 * @method static Builder|\App\Models\LeadType query()
 * @method static bool|null restore()
 * @method static Builder|\App\Models\LeadType whereCreatedAt($value)
 * @method static Builder|\App\Models\LeadType whereDeletedAt($value)
 * @method static Builder|\App\Models\LeadType whereId($value)
 * @method static Builder|\App\Models\LeadType whereName($value)
 * @method static Builder|\App\Models\LeadType whereOrganizationId($value)
 * @method static Builder|\App\Models\LeadType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadType withoutTrashed()
 * @mixin \Eloquent
 */
class LeadType extends Model
{
    use SoftDeletes;

    protected $table = 'lead_types';
    protected $fillable = ['organization_id', 'name'];

    /**
     * Relationship LeadType to Organization as many-to-one
     *
     * @return HasOne
     */
    public function organization()
    {
        return $this->hasOne(
            'App\Models\Organization',
            'id', 'organization_id'
        );
    }
}
