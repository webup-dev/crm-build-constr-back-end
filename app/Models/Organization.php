<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model App\Models\Organization
 *
 * @category Model
 * @package  Organization
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int         $id
 * @property string|null $order
 * @property string      $name
 * @property int|null    $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int         $level
 *
 * @property-read Collection|LeadType[]     $leadType
 * @property-read Collection|Organization[] $childOrganizations
 * @property-read Organization              $parentOrganization
 * @property-read Collection|User_profile[] $user_profile
 * @property-read Collection|Customer[]     $customer
 * @property-read Collection|LeadSource[]   $leadSources
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LeadStatus[] $leadStatuses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RwStage[] $rwStages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Workflow[] $workflows
 *
 * @method static Builder|Organization newModelQuery()
 * @method static Builder|Organization newQuery()
 * @method static Builder|Organization query()
 * @method static Builder|Organization whereCreatedAt($value)
 * @method static Builder|Organization whereId($value)
 * @method static Builder|Organization whereName($value)
 * @method static Builder|Organization whereOrder($value)
 * @method static Builder|Organization whereParentId($value)
 * @method static Builder|Organization whereUpdatedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Organization onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Organization whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Organization withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Organization withoutTrashed()
 * @method static Builder|Organization whereLevel($value)
 *
 * @mixin \Eloquent
 */
class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = ['level', 'order', 'name', 'parent_id'];

    /**
     * Organization - Organization: one-to-many
     * Get the Organization that owns the Organization.
     *
     * @return BelongsTo
     */
    public function parentOrganization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Organization - Organization: one-to-many
     *
     * Get the Organizations for the Organization.
     *
     * @return HasMany
     */
    public function childOrganizations()
    {
        return $this->hasMany('App\Models\Organization', 'parent_id');
    }

    /**
     * Organizations <-> user_profiles: one-to-many
     *
     * Get the user_profile that belongs the organization.
     *
     * @return HasMany
     */
    public function user_profile()
    {
        return $this->hasMany(
            'App\Models\User_profile',
            'department_id',
            'id'
        );
    }

    /**
     * Organizations <-> customers: one-to-many
     *
     * Get the customer that belongs to the organization.
     *
     * @return HasMany
     */
    public function customer()
    {
        return $this->hasMany(
            'App\Models\Customer',
            'organization_id',
            'id'
        );
    }

    /**
     * Organizations - lead_sources: one-to-many
     *
     * Get the lead_sources for the Organization.
     *
     * @return HasMany
     */
    public function leadSources()
    {
        return $this->hasMany(
            'App\Models\LeadSource',
            'organization_id',
            'id'
        );
    }

    /**
     * Relationship LeadType to Organization as many-to-one
     *
     * @return HasMany
     */
    public function leadType()
    {
        return $this->hasMany(
            'App\Models\LeadType',
            'organization_id',
            'id'
        );
    }

    /**
     * Organization - LeadStatus: one-to-many
     *
     * Get the lead_statuses for the Organization.
     *
     * @return HasMany
     */
    public function leadStatuses()
    {
        return $this->hasMany(
            'App\Models\LeadStatus',
            'organization_id',
            'id'
        );
    }

    /**
     * Organization - RwStage: one-to-many
     *
     * Get the RwStages of the Organization.
     *
     * @return HasMany
     */
    public function rwStages()
    {
        return $this->hasMany(
            'App\Models\Stage',
            'organization_id',
            'id'
        );
    }

    /**
     * Organization - workflow: one-to-many
     *
     * Get the workflows of the Organization.
     *
     * @return HasMany
     */
    public function workflows()
    {
        return $this->hasMany(
            'App\Models\Workflow',
            'organization_id',
            'id'
        );
    }

}
