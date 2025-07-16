<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getIsSaleAttribute()
    {
        return $this->sale_price > 0
            && $this->status == 1
            && $this->quantity > 0
            && $this->is_on_sale_from < Carbon::now()
            && $this->is_on_sale_to > Carbon::now();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
