<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bootstrap 5 styling for all paginators.
        Paginator::useBootstrapFive();

        // Share navigation data (categories, cart/wishlist counts, footer pages)
        // with the storefront layout and its partials.
        View::composer(['layouts.app', 'partials.frontend.*'], function ($view) {
            $cart = app(CartService::class);

            $view->with([
                'navCategories' => Category::active()->parents()->orderBy('sort_order')->orderBy('name')->take(8)->get(),
                'cartCount' => $cart->count(),
                'wishlistCount' => auth()->check() ? auth()->user()->wishlists()->count() : 0,
                'footerPages' => Page::active()->orderBy('title')->get(),
            ]);
        });
    }
}
