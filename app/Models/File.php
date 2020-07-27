<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string $owner_object_type
 * @property string $owner_object_id
 * @property string|null $description
 * @property string $filename
 * @property int $owner_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereOwnerObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereOwnerObjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereOwnerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 */
class File extends Model
{
    use SoftDeletes;

    protected $fillable = ['owner_object_type', 'owner_object_id', 'description', 'filename', 'owner_user_id', 'deleted_at'];

    /**
     * users <-> files: one-to-many
     *
     * Get the user that is the owner of the file.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'owner_user_id', 'id');
    }
}
