<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Action_role
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $action
 * @property string $role_ids
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role whereRoleIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Action_role whereUpdatedAt($value)
 */
class Action_role extends Model
{
    use SoftDeletes;

    protected $table = 'action_roles';
    protected $fillable = ['action', 'role_ids'];
}
