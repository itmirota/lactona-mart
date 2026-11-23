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
        if (! Schema::hasColumn('products', 'nutrition_facts')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('nutrition_facts')->nullable();
            });
        }

        $lactonaIbuNutritionFacts = [
            'takaran_saji' => ['per_serving' => '3 sendok makan penuh (40g)'],
            'jumlah_sajian_per_kemasan' => ['per_serving' => '± 5'],
            'energi_total' => ['per_serving' => '170 kkal'],
            'energi_dari_lemak' => ['per_serving' => '35 kkal'],
            'energi_dari_lemak_jenuh' => ['per_serving' => '15 kkal'],
            'lemak_total' => ['per_serving' => '4 g', 'pregnancy' => '5%', 'lactation' => '4%'],
            'lemak_trans' => ['per_serving' => '0 g', 'pregnancy' => '-', 'lactation' => '-'],
            'kolesterol' => ['per_serving' => '5 mg', 'pregnancy' => '2%', 'lactation' => '2%'],
            'lemak_jenuh' => ['per_serving' => '2 g', 'pregnancy' => '9%', 'lactation' => '9%'],
            'protein' => ['per_serving' => '8 g', 'pregnancy' => '11%', 'lactation' => '11%'],
            'karbohidrat_total' => ['per_serving' => '24 g', 'pregnancy' => '7%', 'lactation' => '7%'],
            'serat_pangan' => ['per_serving' => '3 g', 'pregnancy' => '10%', 'lactation' => '9%'],
            'serat_pangan_larut' => ['per_serving' => '2 g', 'pregnancy' => '-', 'lactation' => '-'],
            'gula_total' => ['per_serving' => '18 g', 'pregnancy' => '-', 'lactation' => '-'],
            'sukrosa' => ['per_serving' => '8 g', 'pregnancy' => '-', 'lactation' => '-'],
            'natrium' => ['per_serving' => '75 mg', 'pregnancy' => '5%', 'lactation' => '5%'],
            'vitamin_a' => ['per_serving' => '-', 'pregnancy' => '20%', 'lactation' => '20%'],
            'vitamin_d3' => ['per_serving' => '-', 'pregnancy' => '10%', 'lactation' => '10%'],
            'vitamin_e' => ['per_serving' => '-', 'pregnancy' => '25%', 'lactation' => '20%'],
            'vitamin_k' => ['per_serving' => '-', 'pregnancy' => '6%', 'lactation' => '6%'],
            'vitamin_b1' => ['per_serving' => '-', 'pregnancy' => '30%', 'lactation' => '30%'],
            'vitamin_b2' => ['per_serving' => '-', 'pregnancy' => '25%', 'lactation' => '25%'],
            'vitamin_b3' => ['per_serving' => '-', 'pregnancy' => '30%', 'lactation' => '30%'],
            'vitamin_b5' => ['per_serving' => '-', 'pregnancy' => '25%', 'lactation' => '20%'],
            'vitamin_b6' => ['per_serving' => '-', 'pregnancy' => '35%', 'lactation' => '35%'],
            'vitamin_b9' => ['per_serving' => '-', 'pregnancy' => '35%', 'lactation' => '40%'],
            'vitamin_b12' => ['per_serving' => '-', 'pregnancy' => '20%', 'lactation' => '20%'],
            'vitamin_c' => ['per_serving' => '-', 'pregnancy' => '35%', 'lactation' => '30%'],
            'kolin' => ['per_serving' => '-', 'pregnancy' => '10%', 'lactation' => '10%'],
            'biotin' => ['per_serving' => '-', 'pregnancy' => '80%', 'lactation' => '70%'],
            'kalium' => ['per_serving' => '-', 'pregnancy' => '6%', 'lactation' => '6%'],
            'kalsium' => ['per_serving' => '-', 'pregnancy' => '25%', 'lactation' => '25%'],
            'fosfor' => ['per_serving' => '-', 'pregnancy' => '30%', 'lactation' => '30%'],
            'magnesium' => ['per_serving' => '-', 'pregnancy' => '15%', 'lactation' => '20%'],
            'zink' => ['per_serving' => '-', 'pregnancy' => '15%', 'lactation' => '15%'],
            'zat_besi' => ['per_serving' => '-', 'pregnancy' => '35%', 'lactation' => '40%'],
            'mangan' => ['per_serving' => '-', 'pregnancy' => '20%', 'lactation' => '15%'],
            'iodium' => ['per_serving' => '-', 'pregnancy' => '30%', 'lactation' => '25%'],
            'selenium' => ['per_serving' => '-', 'pregnancy' => '35%', 'lactation' => '30%'],
            'dha' => ['per_serving' => '11 mg'],
            'omega_3' => ['per_serving' => '55,8 mg'],
            'omega_6' => ['per_serving' => '629,4 mg'],
        ];

        DB::table('products')
            ->where('name', 'Lactona Ibu')
            ->whereNull('nutrition_facts')
            ->update([
                'nutrition_facts' => json_encode($lactonaIbuNutritionFacts, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('nutrition_facts');
        });
    }
};
