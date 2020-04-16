<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model App\Models\Workflow
 *
 * @category Model
 * @package Workflow
 * @author Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link Model
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property string $workflow_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stage[] $stages
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Workflow onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Workflow whereWorkflowType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Workflow withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Workflow withoutTrashed()
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
