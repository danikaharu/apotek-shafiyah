<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'discount_amount',
        'min_quantity',
        'start_date',
        'end_date',
        'show_on_dashboard',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }
}
