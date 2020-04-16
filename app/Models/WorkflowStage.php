<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Model App\Models\WorkflowStage
 *
 * @category Model
 * @package  Workflow
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int $id
 * @property int $workflow_id
 * @property int $stage_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static bool|null forceDelete()
 * @method static Builder|WorkflowStage newModelQuery()
 * @method static Builder|WorkflowStage newQuery()
 * @method static \Illuminate\Database\Query\Builder|WorkflowStage onlyTrashed()
 * @method static Builder|WorkflowStage query()
 * @method static bool|null restore()
 * @method static Builder|WorkflowStage whereCreatedAt($value)
 * @method static Builder|WorkflowStage whereDeletedAt($value)
 * @method static Builder|WorkflowStage whereId($value)
 * @method static Builder|WorkflowStage whereStageId($value)
 * @method static Builder|WorkflowStage whereUpdatedAt($value)
 * @method static Builder|WorkflowStage whereWorkflowId($value)
 * @method static \Illuminate\Database\Query\Builder|WorkflowStage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WorkflowStage withoutTrashed()
 *
 * @mixin \Eloquent
 */
class WorkflowStage extends Model
{
    use SoftDeletes;

    protected $table = 'workflow_stages';
    protected $fillable = [
        'name',
        'workflow_id',
        'stage_id',
        'deleted_at'
    ];
}
