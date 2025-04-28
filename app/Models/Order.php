<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Order
    protected $fillable = ['admin_id', 'customer_id', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function detail_order()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function status()
    {
        if ($this->status == 1) {
            return 'Belum Diproses';
        } elseif ($this->status == 2) {
            return 'Selesai';
        } elseif ($this->status == 3) {
            return 'Dibatalkan';
        } else {
            return 'Sedang Diproses';
        }
    }
}
