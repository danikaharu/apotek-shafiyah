<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('member_levels')->insert([
            ['name' => 'Basic', 'min_transactions' => 0, 'discount_percent' => 0],
            ['name' => 'Silver', 'min_transactions' => 5, 'discount_percent' => 5],
            ['name' => 'Gold', 'min_transactions' => 35, 'discount_percent' => 10],
            ['name' => 'Platinum', 'min_transactions' => 100, 'discount_percent' => 15],
        ]);
    }
}
