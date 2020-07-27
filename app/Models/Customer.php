<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property int $organization_id
 * @property string $type
 * @property string $city
 * @property string $line_1
 * @property string|null $line_2
 * @property string|null $state
 * @property string|null $zip
 * @property int $customer_owner_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomerFile[] $customer_file
 * @property-read \App\Models\User $customer_owner_user
 * @property-read \App\Models\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCustomerOwnerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withoutTrashed()
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $fillable = [
        'name',
        'type',
        'city',
        'organization_id',
        'line_1',
        'line_2',
        'state',
        'zip',
        'customer_owner_user_id',
        'deleted_at'
    ];

    /**
     * user <-> customer: many-to-many
     *
     * The users that belong to the customer.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_customers');
    }

    /**
     * user_owner <-> customer: one-to-many
     *
     * The user_owner that belongs to the customer.
     */
    public function customer_owner_user()
    {
        return $this->belongsTo('App\Models\User', 'customer_owner_user_id', 'id');
    }

    /**
     * organizations <-> customer: one-to-many
     *
     * Get the organization that owns the customer.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id', 'id');
    }

    /**
     * customer <-> file: one-to-many
     *
     * Get the customer-file record associated with the customer.
     */
    public function customer_file()
    {
        return $this->hasMany('App\Models\CustomerFile', 'customer_id');
    }
}
