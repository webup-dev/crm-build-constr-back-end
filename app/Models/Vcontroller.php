<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vcontroller
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Method[] $methods
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vcontroller onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vcontroller whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vcontroller withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vcontroller withoutTrashed()
 */
class Vcontroller extends Model
{
    use SoftDeletes;

    protected $table = 'controllers';
    protected $fillable = ['name'];

    /**
     * Controller - method: one-to-many
     *
     * Get the methods for the controller.
     */
    public function methods()
    {
        return $this->hasMany('App\Models\Method', 'controller_id');
    }
}
