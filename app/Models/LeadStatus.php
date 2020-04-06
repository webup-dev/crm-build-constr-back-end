<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\LeadStatus
 *
 * @category Model
 * @package  LeadStatus
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int         $id
 * @property string      $name
 * @property int         $organization_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null    $parent_id
 * @property string|null $other_reason
 *
 * @property-read \App\Models\Organization $organization
 *
 * @method static bool|null forceDelete()
 * @method static Builder|\App\Models\LeadStatus newModelQuery()
 * @method static Builder|\App\Models\LeadStatus newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadStatus onlyTrashed()
 * @method static Builder|\App\Models\LeadStatus query()
 * @method static bool|null restore()
 * @method static Builder|\App\Models\LeadStatus whereCreatedAt($value)
 * @method static Builder|\App\Models\LeadStatus whereDeletedAt($value)
 * @method static Builder|\App\Models\LeadStatus whereId($value)
 * @method static Builder|\App\Models\LeadStatus whereName($value)
 * @method static Builder|\App\Models\LeadStatus whereOrganizationId($value)
 * @method static Builder|\App\Models\LeadStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\LeadStatus withoutTrashed()
 * @method static Builder|\App\Models\LeadStatus whereOtherReason($value)
 * @method static Builder|\App\Models\LeadStatus whereParentId($value)
 *
 * @mixin \Eloquent
 */
class LeadStatus extends Model
{
    use SoftDeletes;

    protected $table = 'lead_statuses';
    protected $fillable = [
        'name',
        'organization_id',
        'parent_id',
        'deleted_at'
    ];

    /**
     * LeadStatus <-> organization: many-to-one
     *
     * Get the organization that owns the LeadStatus
     *
     * @return BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(
            'App\Models\Organization',
            'organization_id',
            'id'
        );
    }
}
