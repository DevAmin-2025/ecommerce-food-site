<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $guarded = [];
    protected $table = 'wishlist';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
