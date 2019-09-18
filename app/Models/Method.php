<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class Method extends Model
{
    protected $fillable = ['name', 'controller_id'];

    /**
     * Get the controller that owns the method.
     */
    public function vcontroller()
    {
        return $this->belongsTo('App\Models\Vcontroller');
    }
}
