<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'min_transactions', 'discount_percent'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
