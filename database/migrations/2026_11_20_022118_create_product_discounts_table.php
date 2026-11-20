<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('promotion_name');
            $table->text('description')->nullable();
            $table->string('discount_type', 20);
            $table->decimal('discount_value', 12, 2);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active', 'starts_at']);
            $table->index(['is_active', 'starts_at', 'ends_at']);
        });

        if (Schema::hasColumn('products', 'discount_percentage')) {
            $timestamp = now();

            DB::table('products')
                ->where('discount_percentage', '>', 0)
                ->orderBy('id')
                ->get(['id', 'discount_percentage'])
                ->each(function (object $product) use ($timestamp): void {
                    DB::table('product_discounts')->insert([
                        'product_id' => $product->id,
                        'promotion_name' => 'Diskon produk sebelumnya',
                        'description' => 'Diskon ini dipindahkan dari pengaturan diskon produk sebelumnya.',
                        'discount_type' => 'percentage',
                        'discount_value' => $product->discount_percentage,
                        'starts_at' => $timestamp,
                        'ends_at' => null,
                        'is_active' => true,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
    }
};
