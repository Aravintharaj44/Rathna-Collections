<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Populate categories, brands, products, variants, a coupon, banners and
     * CMS pages so the storefront and admin panel are immediately usable.
     */
    public function run(): void
    {
        // Categories.
        $categories = collect(['Men', 'Women', 'Kids', 'Accessories'])->map(function ($name, $i) {
            return Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_featured' => true, 'status' => true, 'sort_order' => $i]
            );
        });

        // Brands.
        $brands = collect(['Rathna Classic', 'Urban Threads', 'Little Stars'])->map(function ($name) {
            return Brand::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'status' => true]);
        });

        // Products (a few per category with variants).
        $samples = [
            ['Cotton Formal Shirt', 'Men', 1299, 999, 'men', 'Cotton'],
            ['Slim Fit Jeans', 'Men', 1999, 1499, 'men', 'Denim'],
            ['Floral Print Kurti', 'Women', 1499, 1099, 'women', 'Rayon'],
            ['Silk Saree', 'Women', 3999, 2999, 'women', 'Silk'],
            ['Kids Cartoon T-Shirt', 'Kids', 599, 449, 'kids', 'Cotton'],
            ['Leather Belt', 'Accessories', 899, null, 'unisex', 'Leather'],
            ['Casual Polo T-Shirt', 'Men', 799, 599, 'men', 'Cotton'],
            ['Designer Handbag', 'Accessories', 2499, 1999, 'women', 'Synthetic'],
        ];

        foreach ($samples as $i => [$name, $catName, $price, $offer, $gender, $fabric]) {
            $category = $categories->firstWhere('name', $catName);

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brands->random()->id,
                    'name' => $name,
                    'sku' => 'RC-'.strtoupper(Str::random(6)),
                    'short_description' => "Premium {$fabric} {$name} from Rathna Collections.",
                    'description' => "This {$name} is crafted from high-quality {$fabric}. Comfortable, durable and stylish — perfect for everyday wear and special occasions.",
                    'price' => $price,
                    'offer_price' => $offer,
                    'tax' => 5,
                    'stock' => 50,
                    'gender' => $gender,
                    'fabric' => $fabric,
                    'sleeve_type' => in_array($gender, ['men', 'women']) ? 'Full Sleeve' : null,
                    'is_featured' => $i < 4,
                    'is_new_arrival' => $i >= 2 && $i < 6,
                    'is_best_seller' => $i % 2 === 0,
                    'status' => true,
                ]
            );

            // Add size variants if none exist yet.
            if ($product->variants()->count() === 0) {
                foreach (['S', 'M', 'L', 'XL'] as $size) {
                    $product->variants()->create([
                        'color' => 'Default',
                        'size' => $size,
                        'additional_price' => $size === 'XL' ? 100 : 0,
                        'stock' => 15,
                    ]);
                }
            }
        }

        // Coupon.
        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            ['type' => 'percent', 'value' => 10, 'min_purchase' => 500, 'max_discount' => 500, 'usage_limit' => 100, 'status' => true]
        );

        // CMS pages.
        $pages = [
            ['About Us', '<p>Rathna Collections is your trusted destination for quality textile fashion.</p>'],
            ['Contact', '<p>Email: support@rathnacollections.test<br>Phone: +91 00000 00000</p>'],
            ['Privacy Policy', '<p>We respect your privacy and protect your personal data.</p>'],
            ['Terms &amp; Conditions', '<p>By using our website you agree to our terms of service.</p>'],
            ['Return Policy', '<p>Returns are accepted within 7 days of delivery.</p>'],
        ];
        foreach ($pages as [$title, $content]) {
            Page::updateOrCreate(['slug' => Str::slug($title)], ['title' => $title, 'content' => $content, 'status' => true]);
        }

        // Banner (uses a solid color placeholder; admin can replace the image).
        Banner::updateOrCreate(
            ['title' => 'New Season Sale'],
            ['type' => 'slider', 'subtitle' => 'Up to 40% off', 'image' => 'banners/placeholder.jpg', 'link' => '/shop', 'button_text' => 'Shop Now', 'status' => false]
        );
    }
}
