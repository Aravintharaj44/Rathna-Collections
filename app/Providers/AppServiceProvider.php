<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $view->with([
                'navCategories' => $this->navigationTree(),
                'cartCount'     => app(CartService::class)->count(),
                'wishlistCount' => auth()->check() ? auth()->user()->wishlists()->count() : 0,
                'footerPages'   => Cache::remember(
                    'footer_pages',
                    now()->addHour(),
                    fn () => Page::active()->orderBy('title')->get()
                ),
            ]);
        });
    }

    /**
     * Top-level categories with three levels of descendants, cached.
     */
    protected function navigationTree()
    {
        // return Cache::remember('nav_categories', now()->addHour(), function () {
        //     $sorted = fn ($q) => $q->where('status', true)
        //         ->orderBy('sort_order')
        //         ->orderBy('name');

        //     return Category::query()
        //         ->active()
        //         ->parents()
        //         ->with(['children' => fn ($q) => $sorted($q)
        //             ->with(['children' => fn ($q) => $sorted($q)
        //                 ->with(['children' => $sorted]),
        //             ]),
        //         ])
        //         ->orderBy('sort_order')
        //         ->orderBy('name')
        //         ->take(8)
        //         ->get();
        // });
        $sorted = fn ($q) => $q->where('status', true)
                ->orderBy('sort_order')
                ->orderBy('name');

            return Category::query()
                ->active()
                ->parents()
                ->with(['children' => fn ($q) => $sorted($q)
                    ->with(['children' => fn ($q) => $sorted($q)
                        ->with(['children' => $sorted]),
                    ]),
                ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(8)
                ->get();

    }
}