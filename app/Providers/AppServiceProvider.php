<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProductType;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.header', function ($view) {
            $loai_sp = ProductType::all();
            $view->with('loai_sp', $loai_sp);
        });

        View::composer('pages.product_type', function ($view) {
            $product_new = Product::where('new', 1)->orderBy('id', 'DESC')->skip(1)->take(8)->get();
            $view->with('product_new', $product_new);
        });
    }
}
