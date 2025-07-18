<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    public function getIsSaleAttribute(): bool
    {
        return $this->sale_price > 0
            && $this->status == 1
            && $this->quantity > 0
            && $this->is_on_sale_from < Carbon::now()
            && $this->is_on_sale_to > Carbon::now();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function scopeSearch(Builder $query, string|null $search): void
    {
        $query->where('name', 'LIKE', '%' . trim($search) . '%')
            ->orWhere('description', 'LIKE', '%' . trim($search) . '%');
    }

    public function scopeFilterCategory(Builder $query): void
    {
        if (request()->has('category')) {
            $query->where('category_id', request()->category);
        }
    }

    public function scopeFilterAttribute(Builder $query): void
    {
        switch (request()->sortBy) {
            case 'max':
                $query->orderByDesc('price');
                break;
            case 'min':
                $query->orderBy('price');
                break;
            case 'bestseller':
                // To be completed
                break;
            case 'sale':
                $query->where('sale_price', '>', 0)
                    ->where('is_on_sale_from', '<', Carbon::now())
                    ->where('is_on_sale_to', '>', Carbon::now());
                break;
        }
    }
}
