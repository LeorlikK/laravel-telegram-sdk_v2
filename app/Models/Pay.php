<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pay extends Model
{
    use HasFactory;

    protected $table = 'pays';
    protected $guarded = [];
    public $timestamps = false;

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id' , 'id');
    }
}
