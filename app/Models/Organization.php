<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization
 *
 * @property int $id
 * @property string|null $order
 * @property string $name
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $childOrganizations
 * @property-read \App\Models\Organization $parentOrganization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Organization extends Model
{
    protected $fillable = ['order', 'name', 'parent_id'];

    /**
     * Organization - Organization: one-to-many
     * Get the Organization that owns the Organization.
     */
    public function parentOrganization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Organization - Organization: one-to-many
     *
     * Get the Organizations for the Organization.
     */
    public function childOrganizations()
    {
        return $this->hasMany('App\Models\Organization', 'parent_id');
    }
}
