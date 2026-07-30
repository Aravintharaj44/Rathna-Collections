<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesFileUpload;

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->when($request->q, fn ($q) => $q->where('name', 'like', "%{$request->q}%")->orWhere('sku', 'like', "%{$request->q}%"))
            ->when($request->category, fn ($q) => $q->where('category_id', $request->category))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $data = $this->prepare($request);
            $data['thumbnail'] = $this->storeFile($request->file('thumbnail'), 'products');

            $product = Product::create($data);

            $this->syncGallery($request, $product);
            $this->syncVariants($request, $product);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'variants']);

        return view('admin.products.edit', array_merge($this->formData(), compact('product')));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $data = $this->prepare($request);
            $data['thumbnail'] = $this->replaceFile($request->file('thumbnail'), 'products', $product->thumbnail);

            $product->update($data);

            $this->deleteRemovedGalleryImages($request, $product);
            $this->syncGallery($request, $product);
            $this->syncVariants($request, $product);
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteFile($product->thumbnail);
        foreach ($product->images as $image) {
            $this->deleteFile($image->image);
        }
        $product->delete(); // cascades images + variants via FK

        return back()->with('success', 'Product deleted.');
    }

    // ----- Helpers -------------------------------------------------------

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ];
    }

    private function prepare(ProductRequest $request): array
    {
        $data = $request->safe()->only([
            'category_id', 'brand_id', 'name', 'sku', 'short_description', 'description',
            'price', 'offer_price', 'tax', 'stock', 'gender', 'fabric', 'sleeve_type',
            'meta_title', 'meta_description',
        ]);

        $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        foreach (['is_featured', 'is_new_arrival', 'is_best_seller', 'status'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        return $data;
    }

    /**
     * Append newly uploaded gallery images.
     */
    private function syncGallery(ProductRequest $request, Product $product): void
    {
        foreach ((array) $request->file('gallery', []) as $file) {
            $path = $this->storeFile($file, 'products/gallery');
            if ($path) {
                $product->images()->create(['image' => $path]);
            }
        }
    }

    /**
     * Delete gallery images the admin ticked for removal.
     */
    private function deleteRemovedGalleryImages(Request $request, Product $product): void
    {
        foreach ((array) $request->input('remove_images', []) as $imageId) {
            $image = $product->images()->find($imageId);
            if ($image) {
                $this->deleteFile($image->image);
                $image->delete();
            }
        }
    }

    /**
     * Rebuild the variant matrix from the submitted parallel arrays.
     * Empty rows (no color and no size) are skipped.
     */
    private function syncVariants(Request $request, Product $product): void
    {
        $colors = $request->input('variant_color', []);
        $sizes = $request->input('variant_size', []);
        $prices = $request->input('variant_price', []);
        $stocks = $request->input('variant_stock', []);

        $product->variants()->delete();

        $seen = [];
        foreach ($colors as $i => $color) {
            $size = $sizes[$i] ?? null;
            if (blank($color) && blank($size)) {
                continue;
            }

            // Skip duplicate color/size combinations (unique constraint).
            $key = strtolower(($color ?? '').'|'.($size ?? ''));
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $product->variants()->create([
                'color' => $color ?: null,
                'size' => $size ?: null,
                'additional_price' => $prices[$i] ?? 0,
                'stock' => $stocks[$i] ?? 0,
            ]);
        }
    }
}
