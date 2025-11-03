<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use App\Models\OrderItems;

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
        'is_active',
        'weight'
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
}
