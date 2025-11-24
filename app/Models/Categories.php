<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * Cast attributes to native types
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Append accessor ke JSON
     */
    protected $appends = ['image_url'];

    /**
     * Relasi: satu kategori memiliki banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }

    /**
     * Get full URL gambar kategori dengan fallback
     */
    public function getImageUrlAttribute()
    {
        // Jika tidak ada gambar, return default
        if (!$this->image) {
            return asset('category/default.png');
        }

        // Jika path dimulai dengan 'categories/', gunakan storage
        if (str_starts_with($this->image, 'categories/')) {
            // Cek apakah file exists di storage
            if (Storage::disk('public')->exists($this->image)) {
                return asset('storage/' . $this->image);
            }
        }

        // Jika path dimulai dengan 'category/' (public), gunakan langsung
        if (str_starts_with($this->image, 'category/')) {
            return asset($this->image);
        }

        // Default: anggap dari storage
        return asset('storage/' . $this->image);
    }
}