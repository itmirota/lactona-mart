<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')->unique();

            // Data pelanggan
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');

            // Total dan status pesanan
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('Menunggu Konfirmasi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
