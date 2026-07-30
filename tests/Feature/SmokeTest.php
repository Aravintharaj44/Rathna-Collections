<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    private function admin(): User
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    private function customer(): User
    {
        return User::where('role', 'customer')->firstOrFail();
    }

    public function test_admin_pages_render(): void
    {
        $admin = $this->admin();
        $product = Product::first();

        $urls = [
            route('admin.dashboard'),
            route('admin.categories.index'), route('admin.categories.create'),
            route('admin.brands.index'), route('admin.brands.create'),
            route('admin.products.index'), route('admin.products.create'),
            route('admin.products.edit', $product),
            route('admin.coupons.index'), route('admin.coupons.create'),
            route('admin.banners.index'), route('admin.banners.create'),
            route('admin.pages.index'), route('admin.pages.create'),
            route('admin.customers.index'),
            route('admin.orders.index'),
            route('admin.settings.edit'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_customer_pages_render(): void
    {
        $customer = $this->customer();

        $urls = [
            route('account.dashboard'), route('account.orders'),
            route('account.profile'), route('account.addresses'), route('wishlist.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($customer)->get($url)->assertOk();
        }
    }

    public function test_add_to_cart_and_place_cod_order(): void
    {
        $customer = $this->customer();
        $product = Product::where('stock', '>', 0)->first();

        // Add to cart (route binds product by slug).
        $this->actingAs($customer)->post(route('cart.store', $product), ['quantity' => 2])->assertRedirect();

        $this->actingAs($customer)->get(route('checkout.index'))->assertOk();

        $before = Order::count();
        $this->actingAs($customer)->post(route('checkout.place'), [
            'name' => 'Test Buyer', 'phone' => '9999999999',
            'line1' => '1 Test St', 'city' => 'Chennai', 'state' => 'TN', 'pincode' => '600001',
            'payment_method' => 'cod',
        ])->assertRedirect();

        $this->assertGreaterThan($before, Order::count(), 'COD order was not created.');
    }

    public function test_admin_category_crud_cycle(): void
    {
        $admin = $this->admin();

        // Clean any leftovers from previous runs (tests hit the real dev DB).
        \App\Models\Category::whereIn('slug', ['smoke-test-category', 'smoke-test-renamed'])->delete();

        // Create.
        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Smoke Test Category', 'status' => '1', 'sort_order' => 0,
        ])->assertRedirect(route('admin.categories.index'));

        $category = \App\Models\Category::where('name', 'Smoke Test Category')->firstOrFail();
        $this->assertSame('smoke-test-category', $category->slug);

        // Update.
        $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Smoke Test Renamed', 'status' => '1', 'sort_order' => 1,
        ])->assertRedirect(route('admin.categories.index'));
        $category = $category->fresh(); // slug regenerated from the new name
        $this->assertSame('Smoke Test Renamed', $category->name);

        // Delete.
        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertRedirect();
        $this->assertNull(\App\Models\Category::find($category->id));
    }
}
