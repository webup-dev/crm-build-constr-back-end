<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Model App\Models\Stages
 *
 * @category Model
 * @package  Stages
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int         $id
 * @property string      $name
 * @property int         $organization_id
 * @property string      $workflow_type
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Workflow[] $stages
 *
 * @method static bool|null forceDelete()
 * @method static Builder|\App\Models\Stage newModelQuery()
 * @method static Builder|\App\Models\Stage newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Stage onlyTrashed()
 * @method static Builder|\App\Models\Stage query()
 * @method static bool|null restore()
 * @method static Builder|\App\Models\Stage whereCreatedAt($value)
 * @method static Builder|\App\Models\Stage whereDeletedAt($value)
 * @method static Builder|\App\Models\Stage whereId($value)
 * @method static Builder|\App\Models\Stage whereName($value)
 * @method static Builder|\App\Models\Stage whereOrganizationId($value)
 * @method static Builder|\App\Models\Stage whereParentId($value)
 * @method static Builder|\App\Models\Stage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Stage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Stage
 *         withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stage
 *         whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stage
 *         whereWorkflowType($value)
 *
 * @mixin \Eloquent
 */
class   Stage extends Model
{
    use SoftDeletes;

    protected $table = 'stages';
    protected $fillable = [
        'name',
        'organization_id',
        'workflow_type',
        'description',
        'deleted_at'
    ];

    /**
     * Stages <-> organization: many-to-one
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

    /**
     * Workflows <-> stages: many-to-many
     *
     * Get the workflows that owns the stages through pivot table
     *
     * @return BelongsToMany
     */
    public function stages()
    {
        return $this->belongsToMany(
            'App\Models\Workflow',
            'workflow_stages'
        )
            ->withTimestamps();
    }
}
