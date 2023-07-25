<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\State
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @mixin \Eloquent
 */
class State extends Model
{
    use HasFactory;

    protected $table = 'states';
    protected $guarded = [];
    public $timestamps = false;

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
