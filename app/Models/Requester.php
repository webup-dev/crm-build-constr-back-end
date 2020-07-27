<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Requester
 *
 * @package App\Models
 * @property int               $id
 * @property int               $organization_id
 * @property string|null       $prefix
 * @property string|null       $first_name
 * @property string|null       $last_name
 * @property string|null       $suffix
 * @property string|null       $email_work
 * @property string|null       $email_personal
 * @property string            $line_1
 * @property string            $line_2
 * @property string            $city
 * @property string            $state
 * @property string            $zip
 * @property string|null       $phone_home
 * @property string|null       $phone_work
 * @property string|null       $phone_extension
 * @property string|null       $phone_mob1
 * @property string|null       $phone_mob2
 * @property string|null       $phone_fax
 * @property string|null       $website
 * @property string|null       $other_source
 * @property string|null       $note
 * @property int               $created_by
 * @property int               $updated_by
 * @property Carbon|null       $deleted_at
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property-read User         $createdBy
 * @property-read Organization $organization
 * @property-read User         $updatedBy
 * @method static bool|null forceDelete()
 * @method static Builder|Requester newModelQuery()
 * @method static Builder|Requester newQuery()
 * @method static \Illuminate\Database\Query\Builder|Requester onlyTrashed()
 * @method static Builder|Requester query()
 * @method static bool|null restore()
 * @method static Builder|Requester whereCity($value)
 * @method static Builder|Requester whereCreatedAt($value)
 * @method static Builder|Requester whereCreatedBy($value)
 * @method static Builder|Requester whereDeletedAt($value)
 * @method static Builder|Requester whereEmailPersonal($value)
 * @method static Builder|Requester whereEmailWork($value)
 * @method static Builder|Requester whereFirstName($value)
 * @method static Builder|Requester whereId($value)
 * @method static Builder|Requester whereLastName($value)
 * @method static Builder|Requester whereLine1($value)
 * @method static Builder|Requester whereLine2($value)
 * @method static Builder|Requester whereNote($value)
 * @method static Builder|Requester whereOrganizationId($value)
 * @method static Builder|Requester whereOtherSource($value)
 * @method static Builder|Requester wherePhoneExtension($value)
 * @method static Builder|Requester wherePhoneFax($value)
 * @method static Builder|Requester wherePhoneHome($value)
 * @method static Builder|Requester wherePhoneMob1($value)
 * @method static Builder|Requester wherePhoneMob2($value)
 * @method static Builder|Requester wherePhoneWork($value)
 * @method static Builder|Requester wherePrefix($value)
 * @method static Builder|Requester whereState($value)
 * @method static Builder|Requester whereSuffix($value)
 * @method static Builder|Requester whereUpdatedAt($value)
 * @method static Builder|Requester whereUpdatedBy($value)
 * @method static Builder|Requester whereWebsite($value)
 * @method static Builder|Requester whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|Requester withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Requester withoutTrashed()
 * @mixin \Eloquent
 * @property int $created_by_id
 * @property int|null $updated_by_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requester whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requester whereUpdatedById($value)
 */
class Requester extends Model
{
    protected $fillable = [
        'organization_id',
        'prefix',
        'first_name',
        'last_name',
        'suffix',
        'email_work',
        'email_personal',
        'line_1',
        'line_2',
        'city',
        'state',
        'zip',
        'phone_home',
        'phone_work',
        'phone_extension',
        'phone_mob1',
        'phone_mob2',
        'phone_fax',
        'website',
        'other_source',
        'note',
        'created_by_id',
        'updated_by_id',
        'deleted_at'
    ];

    use SoftDeletes;

    protected $table = 'requesters';

    /**
     * Requester <-> organization: many-to-one
     *
     * Get the organization that owns the requesters
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
     * Requester <-> user: many-to-one
     *
     * Get the user that owns the requesters
     *
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(
            'App\Models\User',
            'created_by_id',
            'id'
        );
    }

    /**
     * Requester <-> user: many-to-one
     *
     * Get the user that owns the requesters
     *
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(
            'App\Models\User',
            'updated_by_id',
            'id'
        );
    }
}
