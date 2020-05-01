<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lead
 *
 * @package App\Models
 * @property int                             $id
 * @property string                          $name
 * @property int                             $organization_id
 * @property string|null                     $due_date
 * @property string|null                     $anticipated_project_date
 * @property int                             $lead_type_id
 * @property int                             $lead_status_id
 * @property string|null                     $declined_reason_other
 * @property int                             $lead_source_id
 * @property int|null                        $stage_id
 * @property string                          $line_1
 * @property string                          $line_2
 * @property string                          $city
 * @property string                          $state
 * @property string                          $zip
 * @property int                             $requester_id
 * @property string|null                     $note
 * @property int                             $lead_owner_id
 * @property int                             $created_by_id
 * @property int|null                        $updated_by_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User           $creator
 * @property-read \App\Models\User|null      $editor
 * @property-read object                     $stage
 * @property-read \Colllection               $stages
 * @property-read \App\Models\Stage          $leadSource
 * @property-read \App\Models\LeadStatus     $leadStatus
 * @property-read \App\Models\LeadType       $leadType
 * @property-read \App\Models\Organization   $organization
 * @property-read \App\Models\User           $owner
 * @property-read Requester                  $requester
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead newQuery()
 * @method static \Illuminate\Database\Query\Builder|Lead onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereAnticipatedProjectDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereDeclinedReasonOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLeadOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLeadSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLeadStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLeadTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereRequesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereUpdatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lead
 *         whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|Lead withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lead
 *         withoutTrashed()
 * @mixin \Eloquent
 */
class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'leads';
    protected $fillable = [
        'name',
        'organization_id',
        'due_date',
        'anticipated_project_date',
        'lead_type_id',
        'lead_status_id',
        'declined_reason_other',
        'lead_source_id',
        'stage_id',
        'line_1',
        'line_2',
        'city',
        'state',
        'zip',
        'requester_id',
        'note',
        'lead_owner_id',
        'created_by_id',
        'updated_by_id',
        'deleted_at'
    ];

    /**
     * Lead <-> organization: many-to-one
     *
     * Get the organization that owns the leads
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
     * Lead <-> lead_type: many-to-one
     *
     * Get the lead_type that owns the leads
     *
     * @return BelongsTo
     */
    public function leadType()
    {
        return $this->belongsTo(
            'App\Models\LeadType',
            'lead_type_id',
            'id'
        );
    }

    /**
     * Lead <-> lead_status: many-to-one
     *
     * Get the lead_status that owns the leads
     *
     * @return BelongsTo
     */
    public function leadStatus()
    {
        return $this->belongsTo(
            'App\Models\LeadStatus',
            'lead_status_id',
            'id'
        );
    }

    /**
     * Lead <-> lead_sources: many-to-one
     *
     * Get the lead_sources that owns the leads
     *
     * @return BelongsTo
     */
    public function leadSource()
    {
        return $this->belongsTo(
            'App\Models\LeadSource',
            'lead_source_id',
            'id'
        );
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
//     */
//    public function lsCategory()
//    {
//        return $this->hasOneThrough(
//            'App\Models\LsCategory',
//            'App\Models\LeadSource',
//            'category_id',
//            'id',
//            'id'
//        );
//    }

    /**
     * Lead <-> stage: many-to-one
     *
     * Get the current workflow stage
     *
     * @return object Stage
     */
    public function stage()
    {
        return $this->belongsTo(
            'App\Models\Stage',
            'stage_id',
            'id'
        );
    }

    /**
     * Get the current workflow stages
     *
     * @return Colllection Stages
     */
    public function getStagesAttribute()
    {
    }

    /**
     * Lead <-> requester: many-to-one
     *
     * Get the requester that owns the leads
     *
     * @return BelongsTo
     */
    public function requester()
    {
        return $this->belongsTo(
            'App\Models\Requester',
            'requester_id',
            'id'
        );
    }

    /**
     * Lead <-> lead_owner_id: many-to-one
     *
     * Get the user that is the owner of the lead
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(
            'App\Models\User',
            'lead_owner_id',
            'id'
        );
    }

    /**
     * Lead <-> created_by_id: many-to-one
     *
     * Get the user that created the lead
     *
     * @return BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(
            'App\Models\User',
            'created_by_id',
            'id'
        );
    }

    /**
     * Lead <-> creator_by_id: many-to-one
     *
     * Get the user that created the lead
     *
     * @return BelongsTo
     */
    public function editor()
    {
        return $this->belongsTo(
            'App\Models\User',
            'updated_by_id',
            'id'
        );
    }
}
