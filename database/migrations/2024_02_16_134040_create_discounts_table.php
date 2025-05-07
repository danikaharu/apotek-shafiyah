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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->enum('type', ['seasonal', 'volume']); // seasonal = jangka pendek, volume = jangka panjang
            $table->decimal('discount_amount');
            $table->integer('min_quantity')->nullable(); // hanya untuk diskon volume
            $table->date('start_date')->nullable(); // hanya untuk diskon seasonal
            $table->date('end_date')->nullable();   // hanya untuk diskon seasonal
            $table->boolean('show_on_dashboard')->default(true); // untuk checklist
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
