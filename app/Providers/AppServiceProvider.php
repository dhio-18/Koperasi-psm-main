<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Categories;
class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//
                View::composer('*', function ($view) {
            $data = $view->getData();
            if (array_key_exists('categories', $data)) {
                return;
            }


            $categories = Cache::remember('active_categories', 3600, function () {
                return Categories::where('is_active', true)
                    ->select('name', 'slug','image')
                    ->get();
            });
            $view->with('categories', $categories);
        });
    }


}
