<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model
 *
 * @category Model
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Model
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method   static bool|null forceDelete()
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource newModelQuery()
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource newQuery()
 * @method   static \Illuminate\Database\Query\Builder|\App\Models\LeadSource onlyTrashed()
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource query()
 * @method   static bool|null restore()
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereCreatedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereDeletedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereDescription($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereId($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereName($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadSource whereUpdatedAt($value)
 * @method   static \Illuminate\Database\Query\Builder|\App\Models\LeadSource withTrashed()
 * @method   static \Illuminate\Database\Query\Builder|\App\Models\LeadSource withoutTrashed()
 * @mixin    \Eloquent
 */
class LeadSource extends Model
{
    use SoftDeletes;

    protected $table = 'lead_sources';
    protected $fillable = [
        'name',
        'description',
        'deleted_at'
    ];
}
