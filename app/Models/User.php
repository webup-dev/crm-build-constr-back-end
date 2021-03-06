<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Notifications\DatabaseNotificationCollection;


/**
 * App\Models\User
 *
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string|null $remember_token
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\Activity[]                                                                         $activities
 * @property-read Collection|\App\Models\Book[]                                                                             $books
 * @property-read Collection|\App\Models\CustomerComment[]                                                                  $comment
 * @property-read Collection|\App\Models\CustomerFile[]                                                                     $customer_file
 * @property-read Collection|\App\Models\Customer[]                                                                         $customers
 * @property-read DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read Collection|\App\Models\Role[]                                                                             $roles
 * @property-read \App\Models\User_profile
 * @property-read \App\Models\UserDetail                                                                                    $userDetail
 * @property-read Collection|\App\Models\File[] $user_profile
 * @method static bool|null forceDelete()
 * @method static Builder|\App\Models\User newModelQuery()
 * @method static Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static Builder|\App\Models\User query()
 * @method static bool|null restore()
 * @method static Builder|\App\Models\User whereCreatedAt($value)
 * @method static Builder|\App\Models\User whereDeletedAt($value)
 * @method static Builder|\App\Models\User whereEmail($value)
 * @method static Builder|\App\Models\User whereId($value)
 * @method static Builder|\App\Models\User whereName($value)
 * @method static Builder|\App\Models\User wherePassword($value)
 * @method static Builder|\App\Models\User whereRememberToken($value)
 * @method static Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $file
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requester[] $requestersCreated
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requester[] $requestersUpdated
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }

    /**
     * user-roles: many-to-many
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_roles')->withTimestamps();
    }

    /**
     * user-activity: one-to-many
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity', 'user_id');
    }

    /**
     * user <-> user_profile: one-to-one
     *
     * Get the user_profile record associated with the user.
     */
    public function user_profile()
    {
        return $this->hasOne('App\Models\User_profile', 'user_id');
    }

    /**
     * user <-> customer: many-to-many
     *
     * The customers that belong to the user.
     */
    public function customers()
    {
        return $this->belongsToMany('App\Models\Customer', 'user_customers');
    }

    /**
     * user <-> comment: one-to-many
     *
     * Get the customer record associated with the user.
     */
    public function comment()
    {
        return $this->hasMany('App\Models\CustomerComment', 'author_id');
    }

    /**
     * user <-> file: one-to-many
     *
     * Get the customer-file record associated with the user.
     */
    public function customer_file()
    {
        return $this->hasMany('App\Models\CustomerFile', 'owner_user_id');
    }

    /**
     * user <-> user_detail: one-to-one
     */
    public function userDetail()
    {
        return $this->hasOne('App\Models\UserDetail', 'user_id');
    }

    /**
     * user <-> file: one-to-many
     *
     * Get the files record associated with the user.
     */
    public function file()
    {
        return $this->hasMany('App\Models\File', 'owner_user_id');
    }

    /**
     * user <-> requester: one-to-many
     *
     * Get the requests associated with the user.
     */
    public function requestersCreated()
    {
        return $this->hasMany('App\Models\Requester', 'created_by');
    }

    /**
     * user <-> requester: one-to-many
     *
     * Get the requests associated with the user.
     */
    public function requestersUpdated()
    {
        return $this->hasMany('App\Models\Requester', 'updated_by');
    }
}
