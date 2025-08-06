<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'member_level_id', 'first_name', 'last_name', 'birth_date', 'phone_number', 'province', 'district', 'subdistrict', 'postal_code', 'address', 'image'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memberLevel()
    {
        return $this->belongsTo(MemberLevel::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getCartDiscount(): float
    {
        return $this->memberLevel->discount_percent ?? 0;
    }
}
