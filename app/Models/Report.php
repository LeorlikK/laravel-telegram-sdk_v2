<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $guarded = [];

    public function userFrom():BelongsTo
    {
        return $this->belongsTo(User::class, 'from', 'id');
    }

    public function userWhom():BelongsTo
    {
        return $this->belongsTo(User::class, 'to_whom', 'id');
    }
}
