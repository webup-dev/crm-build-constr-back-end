<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUserId($value)
 * @mixin \Eloquent
 * @property mixed $req
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereReq($value)
 */
class Activity extends Model
{
    protected $table = 'activities';
    protected $fillable = ['user_id', 'req'];

    /**
     * user-activity: one-to-many
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
