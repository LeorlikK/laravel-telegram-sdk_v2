<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Button
 *
 * @property int $id
 * @property int $folder_id
 * @property string|null $text
 * @property string|null $callback
 * @property int $sorted_id
 * @property-read \App\Models\Folder $folder
 * @method static \Database\Factories\ButtonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Button newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Button newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Button query()
 * @method static \Illuminate\Database\Eloquent\Builder|Button whereCallback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Button whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Button whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Button whereSortedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Button whereText($value)
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
