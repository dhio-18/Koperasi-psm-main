<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function indexHome()
    {
        $categories = \App\Models\Categories::where('is_active', true)->get();
        $products = \App\Models\Products::with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])->where('is_active', true)->paginate(10);
        return view('home', compact('categories', 'products'));
    }

    public function indexAboutUs()
    {
        return view('pages.about-us');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('q');

        $products = \App\Models\Products::with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])->where('is_active', true)
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
