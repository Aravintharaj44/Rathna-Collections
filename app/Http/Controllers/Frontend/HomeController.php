<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Storefront homepage. Sections are placeholder-safe: each query simply
     * returns an empty collection until the catalog is populated in Phase 2.
     */
    public function index(): View
    {
        return view('frontend.home', [
            'sliders' => Banner::active()->type('slider')->orderBy('sort_order')->get(),
            'featuredCategories' => Category::active()->where('is_featured', true)->take(6)->get(),
            'featuredProducts' => Product::active()->featured()->latest()->take(8)->get(),
            'newArrivals' => Product::active()->newArrivals()->latest()->take(8)->get(),
            'bestSellers' => Product::active()->bestSellers()->latest()->take(8)->get(),
        ]);
    }
}
