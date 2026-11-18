<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['category_id', 'name', 'price', 'description', 'image'];

    // Relasi ke tabel kategori (memudahkan pemanggilan nama kategori nantinya)
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }
    
}