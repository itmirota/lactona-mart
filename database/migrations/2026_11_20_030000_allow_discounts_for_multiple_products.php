<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('discount_product')) {
            Schema::create('discount_product', function (Blueprint $table) {
                $table->foreignId('discount_id')->constrained('product_discounts')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['discount_id', 'product_id']);
                $table->index('product_id');
            });
        }

        if (Schema::hasColumn('product_discounts', 'product_id')) {
            DB::table('product_discounts')
                ->select(['id', 'product_id'])
                ->orderBy('id')
                ->each(function (object $discount): void {
                    DB::table('discount_product')->updateOrInsert(
                        ['discount_id' => $discount->id, 'product_id' => $discount->product_id],
                        ['created_at' => now(), 'updated_at' => now()],
                    );
                });

            Schema::table('product_discounts', function (Blueprint $table): void {
                $table->dropForeign('product_discounts_product_id_foreign');
                $table->dropIndex('product_discounts_product_id_is_active_starts_at_index');
                $table->dropColumn('product_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('product_discounts', 'product_id')) {
            Schema::table('product_discounts', function (Blueprint $table): void {
                $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

        DB::table('discount_product')
            ->orderBy('discount_id')
            ->orderBy('product_id')
            ->get(['discount_id', 'product_id'])
            ->each(function (object $assignment): void {
                DB::table('product_discounts')
                    ->where('id', $assignment->discount_id)
                    ->whereNull('product_id')
                    ->update(['product_id' => $assignment->product_id]);
            });

        Schema::table('product_discounts', function (Blueprint $table): void {
            $table->index(['product_id', 'is_active', 'starts_at']);
        });

        Schema::dropIfExists('discount_product');
    }
};
