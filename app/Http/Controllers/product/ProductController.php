<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Categories; // ✅ Tambahkan ini

class ProductController extends Controller
{
    public function index()
    {
        // Logic to fetch and return products
        $sort = request()->query('sort', 'newest'); // Sorting: read ?sort=...

        $query = Products::where('is_active', true);

        // Sorting: apply order to base listing
        $this->applySort($query, $sort);

        $products = $query->paginate(10)->withQueryString(); // keep query string (sort, q, etc.)
        return view('pages.product.index', compact('products'));
    }

    public function indexCategory($categorySlug)
    {
        // Ambil kategori berdasarkan slug agar breadcrumb tampil benar
        $sort = request()->query('sort', 'newest');

        $category = Categories::select('id', 'name', 'slug', 'is_active')
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Products::where('is_active', true)
            ->where('category_id', $category->id)
            ->with('category:id,name,slug');

        // Terapkan urutan
        $this->applySort($query, $sort);

        $products = $query->paginate(10)->withQueryString();

        // Kirim variabel $category ke view
        return view('pages.product.index', compact('products', 'category'));
    }

    public function show($slug)
    {
        // Logic to fetch and return a single product by its ID
        $product = Products::with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.product.show', compact('product'));
    }

    // Sorting: helper to map sort param to orderBy
    protected function applySort($query, string $sort, bool $isJoined = false): void
    {
        // when joined, prefix columns to avoid ambiguity
        $colCreated = $isJoined ? 'products.created_at' : 'created_at';
        $colName = $isJoined ? 'products.name' : 'name';
        $colPrice = $isJoined ? 'products.price' : 'price';
        $colId = $isJoined ? 'products.id' : 'id';

        switch ($sort) {
            case 'oldest':
                $query->orderBy($colCreated, 'asc')->orderBy($colId, 'asc');
                break;
            case 'price_desc':
                $query->orderBy($colPrice, 'desc')->orderBy($colId, 'desc');
                break;
            case 'price_asc':
                $query->orderBy($colPrice, 'asc')->orderBy($colId, 'asc');
                break;
            case 'name_asc':
                $query->orderBy($colName, 'asc')->orderBy($colId, 'desc');
                break;
            case 'name_desc':
                $query->orderBy($colName, 'desc')->orderBy($colId, 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy($colCreated, 'desc')->orderBy($colId, 'desc');
                break;
        }
    }
}
