<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * App\Models\Folder
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Button> $buttons
 * @property-read int|null $buttons_count
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Tab|null $tab
 * @method static \Database\Factories\FolderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Folder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Folder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Folder query()
 * @mixin \Eloquent
 */
class Folder extends Model
{
    use HasFactory, HasTimestamps;

    protected $table = 'folders';
    protected $guarded = [];

    public function displayViewString():?string
    {
        if ($this->display  > now()){
            return $this->display;
        }

        return null;
    }

    public function displayViewBool():bool
    {
        if ($this->display  > now()){
            return true;
        }

        return false;
    }

    public function tab():BelongsTo
    {
        return $this->belongsTo(Tab::class, 'tab_id', 'id');
    }

    public function buttons():HasMany
    {
        return $this->hasMany(Button::class, 'folder_id', 'id');
    }

    public function product():HasOne
    {
        return $this->hasOne(Product::class, 'folder_id', 'id');
    }

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_folders', 'folder_id', 'product_id');
    }
}
