<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserDetail
 *
 * @property int $id
 * @property int $user_id
 * @property string $prefix
 * @property string $first_name
 * @property string $last_name
 * @property string|null $suffix
 * @property string|null $title
 * @property string|null $work_department
 * @property string|null $role
 * @property string|null $phone_home
 * @property string|null $phone_work
 * @property string|null $phone_extension
 * @property string|null $phone_mob
 * @property string|null $phone_fax
 * @property string|null $email_work
 * @property string $email_personal
 * @property string $line_1
 * @property string $line_2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string|null $status
 * @property int $Contact_owner_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereContactOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereEmailPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereEmailWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePhoneExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePhoneFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePhoneHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePhoneMob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePhoneWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereWorkDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDetail whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserDetail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserDetail withoutTrashed()
 * @mixin \Eloquent
 */
class UserDetail extends Model
{
    use SoftDeletes;

    protected $table = 'user_details';
    protected $fillable = [
        'user_id',
        'prefix',
        'first_name',
        'last_name',
        'suffix',
        'work_title',
        'work_department',
        'work_role',
        'phone_home',
        'phone_work',
        'phone_extension',
        'phone_mob',
        'phone_fax',
        'email_work',
        'email_personal',
        'line_1',
        'line_2',
        'city',
        'state',
        'zip',
        'status'
    ];

    /**
     * user <-> user_details: one-to-one
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
