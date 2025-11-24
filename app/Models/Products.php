<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Storage;

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
        'expired_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function carts()
    {
        return $this->hasMany(Carts::class);
    }

    public function getImageUrlAttribute()
    {
        // Jika tidak ada gambar, return default
        if (!$this->images) {
            return asset('produk/contohproduk.png');
        }

        // Jika path dimulai dengan 'products/', gunakan storage
        if (str_starts_with($this->images, 'products/')) {
            // Cek apakah file exists di storage
            if (Storage::disk('public')->exists($this->images)) {
                return asset('storage/' . $this->images);
            }
        }

        // Jika path dimulai dengan 'produk/' (public), gunakan langsung
        if (str_starts_with($this->images, 'produk/')) {
            return asset($this->images);
        }

        // Default: anggap dari storage
        return asset('storage/' . $this->images);
    }
}
