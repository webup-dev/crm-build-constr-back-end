<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Model
 *
 * @category Model
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 *
 * @property int             $id
 * @property string          $name
 * @property string|null     $description
 * @property Carbon|null     $deleted_at
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 *
 * @property-read LsCategory $lsCategory
 *
 * @method static bool|null forceDelete()
 * @method static Builder|LsCategory newModelQuery()
 * @method static Builder|LsCategory newQuery()
 * @method static Query\Builder|LsCategory onlyTrashed()
 * @method static Builder|LsCategory query()
 * @method static bool|null restore()
 * @method static Builder|LsCategory whereCreatedAt($value)
 * @method static Builder|LsCategory whereDeletedAt($value)
 * @method static Builder|LsCategory whereDescription($value)
 * @method static Builder|LsCategory whereId($value)
 * @method static Builder|LsCategory whereName($value)
 * @method static Builder|LsCategory whereUpdatedAt($value)
 * @method static Query\Builder|LsCategory withTrashed()
 * @method static Query\Builder|LsCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class LsCategory extends Model
{
    use SoftDeletes;

    protected $table = 'ls_categories';
    protected $fillable = [
        'name',
        'description',
        'deleted_at'
    ];

    /**
     * LeadSource <-> Lead Source Category: many-to-one
     *
     * Get the LeadSources that belong to LsCategory
     *
     * @return BelongsTo
     */
    public function lsCategory()
    {
        return $this->belongsTo(
            'App\Models\LsCategory',
            'category_id',
            'id'
        );
    }
}
