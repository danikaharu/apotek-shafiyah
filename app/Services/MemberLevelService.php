<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\MemberLevel;

class MemberLevelService
{
    public static function upgradeLevel(Customer $customer)
    {
        $completedOrders = $customer->orders()->where('status', 2)->count();

        $eligibleLevel = MemberLevel::where('min_transactions', '<=', $completedOrders)
            ->orderByDesc('min_transactions')
            ->first();

        if ($eligibleLevel && $customer->member_level_id !== $eligibleLevel->id) {
            $customer->member_level_id = $eligibleLevel->id;
            $customer->save();
        }
    }
}
