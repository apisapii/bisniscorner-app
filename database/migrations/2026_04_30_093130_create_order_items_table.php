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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // <-- Tambah ini
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // <-- Tambah ini juga buat jaga-jaga
        $table->foreignId('umkm_id')->constrained('users')->onDelete('cascade'); // <-- Tambah ini juga
        $table->integer('quantity');
        $table->integer('price_at_time');
        $table->string('delivery_status')->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
