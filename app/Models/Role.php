<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Model App\Models\Role
 *
 * @category Model
 * @package LeadSourceCategoriess
 * @author Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link Model
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Role onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Role withoutTrashed()
 * @method static Builder|Role whereDeletedAt($value)
 * @mixin \Eloquent
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|User[]   $users
 * @property-read Collection|Method[] $methods
 */
class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    /**
     * Get all users
     * user-roles: many-to-many
     * The users that belong to the role .
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_roles')
            ->withTimestamps();
    }

    /**
     * Get all methods
     * The methods that belong to the role .
     *
     * @return BelongsToMany
     */
    public function methods()
    {
        return $this->belongsToMany('App\Models\Method', 'method_roles')
            ->withTimestamps();
    }
}
