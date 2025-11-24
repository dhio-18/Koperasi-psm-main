<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Products;
use App\Models\CarouselImage;

class HomeController extends Controller
{
    public function indexHome()
    {
        // Ambil kategori aktif dengan URL gambar
        $categories = Categories::where('is_active', true)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image_url, // Menggunakan accessor
                    'is_active' => $category->is_active,
                ];
            });

        // Ambil produk aktif
        $products = Products::with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])
        ->where('is_active', true)
        ->paginate(10);

        $carousels = CarouselImage::active()->ordered()->get();

        return view('home', compact('categories', 'products', 'carousels'));
    }

    public function indexAboutUs()
    {
        return view('pages.about-us');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('q');

        $products = Products::with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])
        ->where('is_active', true)
        ->where(function ($q) use ($searchQuery) {
            $q->where('name', 'like', '%' . $searchQuery . '%')
                ->orWhere('description', 'like', '%' . $searchQuery . '%');
        })
        ->paginate(10);

        return view('pages.product.index', [
            'products' => $products,
            'q' => $searchQuery,
        ]);
    }
}