<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CustomerComment
 *
 * @property int $id
 * @property int $customer_id
 * @property int $author_id
 * @property string $comment
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomerComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerComment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CustomerComment withoutTrashed()
 * @mixin \Eloquent
 */
class CustomerComment extends Model
{
    use SoftDeletes;

    protected $table = 'customer_comments';
    protected $fillable = [
        'customer_id',
        'author_id',
        'comment',
        'parent_id',
        'deleted_at'
    ];

    /**
     * customers <-> comments: one-to-many
     *
     * Get the customer that owns the comments.
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }
}
