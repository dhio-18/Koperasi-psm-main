<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     * id tidak perlu dimasukkan karena auto-increment dari database.
     */
    protected $fillable = [
        'slug',
        'image',
        'name',
        'description',
        'is_active',
    ];

    /**
     * Relasi: satu kategori memiliki banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }
}
