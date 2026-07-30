<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('gateway')->default('razorpay');

            // Razorpay identifiers used for signature verification.
            $table->string('razorpay_order_id')->nullable()->index();
            $table->string('razorpay_payment_id')->nullable()->index();
            $table->string('razorpay_signature')->nullable();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('method')->nullable(); // card, upi, netbanking...
            $table->enum('status', ['created', 'authorized', 'captured', 'failed', 'refunded'])->default('created')->index();
            $table->json('response')->nullable(); // raw gateway payload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
