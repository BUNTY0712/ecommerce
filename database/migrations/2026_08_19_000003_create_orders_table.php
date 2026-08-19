<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('country');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_charge', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method'); // 'razorpay' or 'cod'
            $table->string('payment_status')->default('pending'); // 'pending', 'paid', 'failed', 'cod'
            $table->string('order_status')->default('pending'); // 'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
