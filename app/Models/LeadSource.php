<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Query;
use Illuminate\Support\Carbon;

/**
 * App\Models\LeadSource
 *
 * @category Model
 * @package  LeadSourceCategoriess
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int         $id
 * @property string      $name
 * @property int         $category_id
 * @property int         $organization_id
 * @property string      $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read LsCategory   $lsCategory
 * @property-read Organization $organization
 *
 * @method static bool|null forceDelete()
 * @method static Builder|LeadSource newModelQuery()
 * @method static Builder|LeadSource newQuery()
 * @method static Query\Builder|LeadSource onlyTrashed()
 * @method static Builder|LeadSource query()
 * @method static bool|null restore()
 * @method static Builder|LeadSource whereCategoryId($value)
 * @method static Builder|LeadSource whereCreatedAt($value)
 * @method static Builder|LeadSource whereDeletedAt($value)
 * @method static Builder|LeadSource whereId($value)
 * @method static Builder|LeadSource whereName($value)
 * @method static Builder|LeadSource whereOrganizationId($value)
 * @method static Builder|LeadSource whereStatus($value)
 * @method static Builder|LeadSource whereUpdatedAt($value)
 * @method static Query\Builder|LeadSource withTrashed()
 * @method static Query\Builder|LeadSource withoutTrashed()
 *
 * @mixin \Eloquent
 */
class LeadSource extends Model
{
    use SoftDeletes;

    protected $table = 'lead_sources';
    protected $fillable = [
        'name',
        'category_id',
        'organization_id',
        'status',
        'deleted_at'
    ];

    /**
     * LeadSource <-> organization: many-to-one
     *
     * Get the organization that owns the LeadSource
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

    /**
     * LeadSource <-> Lead Source Category: many-to-one
     *
     * Get the lsCategory that owns the LeadSource
     *
     * @return BelongsTo
     */
    public function lsCategory()
    {
        return $this->belongsTo(
            'App\Models\LsCategory',
            'category_id',
            'id'
        );
    }
}
