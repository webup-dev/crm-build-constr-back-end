<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Method
 *
 * @property int $id
 * @property string $name
 * @property int $controller_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \App\Models\Vcontroller $vcontroller
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Method onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Method withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Method withoutTrashed()
 */
class Method extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'controller_id'];

    /**
     * Controller - method: one-to-many
     * Get the controller that owns the method.
     */
    public function vcontroller()
    {
        return $this->belongsTo('App\Models\Vcontroller');
    }

    /**
     * The methods that belong to the role .
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'method_roles')->withTimestamps();
    }
}
