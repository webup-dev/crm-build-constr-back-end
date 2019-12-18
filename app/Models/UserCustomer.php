<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserCustomer
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserCustomer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCustomer whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserCustomer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserCustomer withoutTrashed()
 * @mixin \Eloquent
 */
class UserCustomer extends Model
{
    use SoftDeletes;

    protected $table = 'user_customers';
    protected $fillable = ['user_id', 'customer_id'];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function customer(){
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }
}
