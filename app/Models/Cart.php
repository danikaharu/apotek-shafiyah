<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'customer_id', 'total_price'];

    public function details()
    {
        return $this->hasMany(DetailCart::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getSubtotal(): float
    {
        return $this->details->sum(function ($item) {
            $product = $item->product;
            $discount = $product->discount;

            $isVolumeDiscount = $discount && $discount->type === 'volume' && $item->amount >= $discount->min_quantity;
            $isSeasonalDiscount = $discount && $discount->type === 'seasonal' &&
                now()->between($discount->start_date, $discount->end_date);

            $discountAmount = ($isVolumeDiscount || $isSeasonalDiscount) ? $discount->discount_amount : 0;
            $finalPrice = $product->price - $discountAmount;

            return $finalPrice * $item->amount;
        });
    }

    public function getCartDiscount(): float
    {
        // Ambil level member dari customer
        $level = $this->customer?->memberLevel;

        return $level?->discount_percent ?? 0;
    }
}
