<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Tab
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Folder> $folders
 * @property-read int|null $folders_count
 * @method static \Database\Factories\TabFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereName($value)
 * @mixin \Eloquent
 */
class Tab extends Model
{
    use HasFactory;

    protected $table = 'tabs';
    protected $guarded = [];
    public $timestamps = false;

    public function folders():HasMany
    {
        return $this->hasMany(Folder::class, 'tab_id', 'id');
    }
}
