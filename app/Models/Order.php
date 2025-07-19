<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function getStatusAttribute(string $status): string
    {
        switch ($status) {
            case '0':
                $status = 'در انتظار پرداخت';
                break;
            case '1':
                $status = 'در حال پردازش';
                break;
            case '2':
                $status = 'ارسال شده';
                break;
            case '3':
                $status = 'کنسل شده';
                break;
            case '4':
                $status = 'مرجوع شده';
                break;
        }
        return $status;
    }

    public function getPaymentStatusAttribute(string $paymentStatus): string
    {
        switch ($paymentStatus) {
            case '0':
                $paymentStatus = 'پرداخت نشده';
                break;
            case '1':
                $paymentStatus = 'پرداخت شده';
                break;
        }
        return $paymentStatus;
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
