<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Model App\Models\Workflow
 *
 * @category Model
 * @package Workflow
 * @author Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link Model
 * @property int                                                   $id
 * @property int                                                   $organization_id
 * @property string                                                $name
 * @property string                                                $workflow_type
 * @property string|null                                           $description
 * @property Carbon|null                                           $deleted_at
 * @property Carbon|null                                           $created_at
 * @property Carbon|null                                           $updated_at
 * @property-read Organization                                     $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|Stage[] $stages
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow newQuery()
 * @method static Builder|Workflow onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workflow whereWorkflowType($value)
 * @method static Builder|Workflow withTrashed()
 * @method static Builder|Workflow withoutTrashed()
 * @mixin \Eloquent
 */
class Workflow extends Model
{
    use SoftDeletes;

    protected $table = 'workflows';
    protected $fillable = [
        'name',
        'organization_id',
        'workflow_type',
        'description',
        'deleted_at'
    ];

    /**
     * Workflows <-> organization: many-to-one
     *
     * Get the organization that owns the workflows
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
     * Workflows <-> stages: many-to-one
     *
     * Get the stages that owns the workflows through pivot table
     *
     * @return BelongsToMany
     */
    public function stages()
    {
        return $this->belongsToMany(
            'App\Models\Stage',
            'workflow_stages'
        )
            ->withPivot('order')
            ->withTimestamps();
    }
}
