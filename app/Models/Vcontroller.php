<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class Vcontroller extends Model
{
    protected $table = 'controllers';
    protected $fillable = ['name'];

    /**
     * Get the methods for the controller.
     */
    public function methods()
    {
        return $this->hasMany('App\Models\Method');
    }
}
