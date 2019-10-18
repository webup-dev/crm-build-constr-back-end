<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User_role
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User_role whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_role onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_role withoutTrashed()
 */
class User_role extends Model
{
    use SoftDeletes;

    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function role(){
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }
}
