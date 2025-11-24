<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Products extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'category_id',
        'slug',
        'images',
        'name',
        'description',
        'price',
        'stock',
        'expired_date',
        'is_active',
        'weight'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'expired_date' => 'date',
    ];

    /**
     * Append accessor ke JSON
     */
    protected $appends = ['image_url'];

    /**
     * Relasi: produk belongs to kategori
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
    /**
     * Get full URL gambar produk dengan fallback
     * UPDATED: Lebih robust untuk handle berbagai case
     */
    public function getImageUrlAttribute()
    {
        // Jika tidak ada gambar atau kosong
        if (empty($this->images)) {
            return asset('produk/contohproduk.png');
        }

        // Jika sudah full URL (http/https), return as is
        if (Str::startsWith($this->images, ['http://', 'https://'])) {
            return $this->images;
        }

        // Jika path dimulai dengan 'products/' (dari storage/upload)
        if (Str::startsWith($this->images, 'products/')) {
            // Return storage URL langsung
            return asset('storage/' . $this->images);
        }

        // Jika path dimulai dengan 'produk/' (old public path)
        if (Str::startsWith($this->images, 'produk/')) {
            return asset($this->images);
        }

        // Default: anggap relative path dari storage
        return asset('storage/' . $this->images);
    }
}
