<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CustomerIndividual
 *
 * @property int $id
 * @property int $customer_id
 * @property string $email
 * @property string $password
 * @property string $billing_address_line_1
 * @property string $billing_address_line_2
 * @property string $billing_city
 * @property string $billing_state
 * @property string $zip
 * @property string $note
 * @property int $created_by_id
 * @property int $updated_by_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User $userCreated
 * @property-read \App\Models\User $userUpdated
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerIndividual onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereBillingAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereBillingAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereBillingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereUpdatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerIndividual whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerIndividual withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerIndividual withoutTrashed()
 * @mixin \Eloquent
 */
class CustomerIndividual extends Model
{
    use SoftDeletes;

    protected $table = 'customer_individuals';
    protected $fillable = [
        'customer_id',
        'email',
        'password',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'note',
        'created_by_id',
        'updated_by_id',
        'deleted_at'
    ];

    /**
     * customer_individual <-> customer: one-to-one
     *
     * Get the customer that belong to the customer individual
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * user <-> customer_individual: one-to-many
     * Get the user that created the customer_individual
     */
    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_by_id', 'id');
    }

    /**
     * user <-> customer_individual: one-to-many
     * Get the user that updated the customer_individual
     */
    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_by_id', 'id');
    }
}
