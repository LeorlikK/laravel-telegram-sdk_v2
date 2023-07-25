<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\Button
 *
 * @property-read \App\Models\Folder|null $folder
 * @method static \Database\Factories\ButtonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Button newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Button newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Button query()
 * @mixin \Eloquent
 */
class Button extends Model
{
    use HasFactory, HasTimestamps;

    protected $table = 'buttons';
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

    public function folder():BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }
}
