<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User_profile
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $title
 * @property int $department_id
 * @property string|null $phone_home
 * @property string|null $phone_work
 * @property string|null $phone_extension
 * @property string|null $phone_mob
 * @property string|null $email_personal
 * @property string|null $email_work
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string $State
 * @property string $zip
 * @property string $status
 * @property string|null $start_date
 * @property string|null $termination_date
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereEmailPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereEmailWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile wherePhoneExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile wherePhoneHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile wherePhoneMob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile wherePhoneWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_profile whereZip($value)
 * @mixin \Eloquent
 * @property string $state
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_profile onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_profile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_profile withoutTrashed()
 */
class User_profile extends Model
{
    use SoftDeletes;

    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'title',
        'department_id',
        'phone_home',
        'phone_work',
        'phone_extension',
        'phone_mob',
        'email_personal',
        'email_work',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip',
        'status',
        'start_date',
        'termination_date',
        'deleted_at'
    ];

    /**
     * user <-> user_profile: one-to-one
     *
     * Get the user that owns the user_profile.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }
}
