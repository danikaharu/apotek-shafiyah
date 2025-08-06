<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('discount_percent')->default(0)->after('total_price');
            $table->integer('discount_amount')->default(0)->after('discount_percent');
            $table->integer('loyalty_discount_percent')->default(0)->after('discount_amount');
            $table->integer('loyalty_discount_amount')->default(0)->after('loyalty_discount_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'discount_percent',
                'discount_amount',
                'loyalty_discount_percent',
                'loyalty_discount_amount'
            ]);
        });
    }
};
