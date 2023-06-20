<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\State
 *
 * @property int $id
 * @property int $user_id
 * @property int $messageId
 * @property string $action
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUserId($value)
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
