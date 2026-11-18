<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_product'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['name'];
}
