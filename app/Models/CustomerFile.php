<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CustomerFile
 *
 * @property int $id
 * @property int $customer_id
 * @property int $author_id
 * @property string $comment
 * @property int|null $parent_id
 * @property int $level
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerFile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerFile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerFile withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $description
 * @property string $filename
 * @property int $owner_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerFile whereOwnerUserId($value)
 */
class CustomerFile extends Model
{
    use SoftDeletes;

    protected $table = 'customer_files';
    protected $fillable = [
        'customer_id',
        'description',
        'filename',
        'owner_user_id',
        'deleted_at'
    ];

    /**
     * customers <-> files: one-to-many
     *
     * Get the customer that owns the files.
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * users <-> files: one-to-many
     *
     * Get the user that saved a file.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'owner_user_id', 'id');
    }
}
