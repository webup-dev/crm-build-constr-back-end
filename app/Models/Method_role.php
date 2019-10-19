<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Method_role
 *
 * @property int $id
 * @property int $method_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Method $method
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method_role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Method_role extends Model
{
    use SoftDeletes;

    protected $table = 'method_roles';
    protected $fillable = ['method_id', 'role_id'];

    public function method(){
        return $this->hasOne('App\Models\Method', 'id', 'method_id');
    }

    public function role(){
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }
}
