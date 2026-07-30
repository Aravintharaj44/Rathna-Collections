<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Shop listing with filters, sorting and pagination.
     */
    public function index(Request $request): View
    {
        $query = Product::query()->active()->with(['brand', 'category']);

        // Text search.
        if ($request->filled('q')) {
            $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$request->q}%")
                ->orWhere('short_description', 'like', "%{$request->q}%"));
        }

        // Category (by slug).
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Brand (by slug).
        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Simple attribute filters.
        $query->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender));
        $query->when($request->filled('fabric'), fn ($q) => $q->where('fabric', $request->fabric));
        $query->when($request->filled('sleeve_type'), fn ($q) => $q->where('sleeve_type', $request->sleeve_type));

        // Price range.
        $query->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->min_price));
        $query->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->max_price));

        // Availability.
        if ($request->availability === 'in_stock') {
            $query->where('stock', '>', 0);
        }

        // Sorting.
        match ($request->sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('is_best_seller')->latest(),
            'rating' => $query->withAvg('approvedReviews', 'rating')->orderByDesc('approved_reviews_avg_rating'),
            default => $query->latest(), // newest
        };

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Product details page.
     */
    public function show(Product $product): View
    {
        abort_unless($product->status, 404);

        $product->load(['images', 'variants', 'brand', 'category', 'approvedReviews.user']);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('frontend.shop.show', compact('product', 'related'));
    }
}
