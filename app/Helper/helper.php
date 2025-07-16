<?php

function ImageUrl(string $img, string $path): string
{
    return config('app.base_url') . $path . $img;
};

function calDiscountPercentage(int $price, int $sale_price): int
{
    return round((($price - $sale_price) / $price) * 100);
}
