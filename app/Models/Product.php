<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'unit_id', 'brand_id', 'type_id', 'code', 'name', 'batch_number', 'price', 'stock', 'image', 'information'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withDefault();
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'detail_purchases', 'product_id', 'purchase_id')
            ->withPivot('price', 'quantity', 'total_price')
            ->withTimestamps();
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
