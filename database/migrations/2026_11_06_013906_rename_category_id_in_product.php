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
        Schema::table('products', function (Blueprint $table) {
            // Mengubah nama dari categoryId menjadi category_id
            $table->renameColumn('categoryId', 'category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengembalikan nama ke categoryId jika migration di-rollback
            $table->renameColumn('category_id', 'categoryId');
        });
    }
};
