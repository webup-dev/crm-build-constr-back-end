<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin \Eloquent
 *
 * @method static bool|null forceDelete()
 * @method static Builder|\App\Models\LsCategory newModelQuery()
 * @method static Builder|\App\Models\LsCategory newQuery()
 * @method static Query\Builder|\App\Models\LsCategory onlyTrashed()
 * @method static Builder|\App\Models\LsCategory query()
 * @method static bool|null restore()
 * @method static Builder|\App\Models\LsCategory whereCreatedAt($value)
 * @method static Builder|\App\Models\LsCategory whereDeletedAt($value)
 * @method static Builder|\App\Models\LsCategory whereDescription($value)
 * @method static Builder|\App\Models\LsCategory whereId($value)
 * @method static Builder|\App\Models\LsCategory whereName($value)
 * @method static Builder|\App\Models\LsCategory whereUpdatedAt($value)
 * @method static Query\Builder|\App\Models\LsCategory withTrashed()
 * @method static Query\Builder|\App\Models\LsCategory withoutTrashed()
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
}
