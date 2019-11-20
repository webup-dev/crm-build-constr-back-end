<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $note
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withoutTrashed()
 * @mixin \Eloquent
 * @property int $user_id
 * @property int $organization_id
 * @property-read \App\Models\CustomerIndividual $customer_individual
 * @property-read \App\Models\Organization $organization
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUserId($value)
 * @property string|null $line_1
 * @property string|null $line_2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereZip($value)
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $fillable = [
        'name',
        'type',
        'organization_id',
        'line_1',
        'line_2',
        'city',
        'state',
        'zip',
        'deleted_at'
    ];

//    /**
//     * user <-> customer: one-to-one
//     *
//     * Get the user that belong to the customer.
//     */
//    public function user()
//    {
//        return $this->belongsTo('App\Models\User', 'user_id', 'id');
//    }
//
    /**
     * organizations <-> customer: one-to-many
     *
     * Get the organization that owns the customer.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id', 'id');
    }
}
