<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $catIbu = CategoryProduct::firstOrCreate(['name' => 'Susu Hamil & Menyusui']);
        $catAnak = CategoryProduct::firstOrCreate(['name' => 'Susu Pertumbuhan Anak']);
        $catDewasa = CategoryProduct::firstOrCreate(['name' => 'Susu Rendah Lemak & Dewasa']);

        Product::firstOrCreate(
            ['name' => 'Lactona Ibu Cokelat'],
            [
                'category_id' => $catIbu->id,
                'price' => 42000,
                'description' => 'Susu yang diformulasikan khusus untuk bantu penuhi kebutuhan nutrisi ibu yang meningkat selama masa kehamilan.',
                'image' => 'produk/Lactona Ibu Cokelat.png',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Lactona Ibu Stroberi'],
            [
                'category_id' => $catIbu->id,
                'price' => 42000,
                'description' => 'Susu formula ibu hamil rasa stroberi lezat yang kaya asam folat, kalsium, dan zat besi.',
                'image' => 'produk/Lactona Ibu Stroberi.png',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Lactona Ibu Vanila'],
            [
                'category_id' => $catIbu->id,
                'price' => 42000,
                'description' => 'Susu bernutrisi tinggi rasa vanila lembut untuk mendukung tumbuh kembang janin dan kesehatan ibu.',
                'image' => 'produk/Lactona Ibu Vanila.png',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Lactona Gold 3+'],
            [
                'category_id' => $catAnak->id,
                'price' => 38000,
                'description' => 'Susu pertumbuhan terfortifikasi vitamin & mineral untuk mendukung kecerdasan dan daya tahan tubuh anak usia 3 tahun ke atas.',
                'image' => 'produk/Lactona Gold 3.png',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Lactona Skim'],
            [
                'category_id' => $catDewasa->id,
                'price' => 34000,
                'description' => 'Susu bubuk skim bebas lemak tinggi kalsium untuk menjaga berat badan ideal dan kesehatan tulang.',
                'image' => 'produk/Lactona Skim.png',
            ]
        );

        $this->call(ProductDiscountSeeder::class);
    }
}
