<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\Pay
 *
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Pay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pay query()
 * @mixin \Eloquent
 */
class Pay extends Model
{
    use HasFactory;

    protected $table = 'pays';
    protected $guarded = [];

    protected $casts = [
        'subscription' => 'datetime',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id' , 'id');
    }
}
