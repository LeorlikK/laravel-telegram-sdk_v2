<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * App\Models\Product
 *
 * @property-read \App\Models\Folder|null $folder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Folder> $folders
 * @property-read int|null $folders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pay> $pays
 * @property-read int|null $pays_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = [];

    public function folder():BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function folders():BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'product_folders', 'product_id', 'folder_id');
    }

    public function pays():HasMany
    {
        return $this->hasMany(Pay::class, 'product_id', 'id');
    }
}
