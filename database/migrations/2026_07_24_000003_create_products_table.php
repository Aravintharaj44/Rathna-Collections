<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();

            // Money stored as decimal to avoid float rounding errors.
            $table->decimal('price', 10, 2);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->decimal('tax', 5, 2)->default(0); // percentage
            $table->unsignedInteger('stock')->default(0);

            // Textile-specific attributes used by the shop filters.
            $table->enum('gender', ['men', 'women', 'kids', 'unisex'])->default('unisex')->index();
            $table->string('fabric')->nullable();
            $table->string('sleeve_type')->nullable();

            $table->string('thumbnail')->nullable();

            // Homepage merchandising flags.
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_new_arrival')->default(false)->index();
            $table->boolean('is_best_seller')->default(false)->index();

            // SEO fields to satisfy the "SEO friendly" goal.
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();

            $table->boolean('status')->default(true)->index();
            $table->timestamps();

            $table->index(['category_id', 'brand_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
