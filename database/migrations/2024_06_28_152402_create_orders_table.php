<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUlid('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignUlid('restaurant_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignUlid('driver_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
//            $table->string('order_status');
            $table->double('total_price');
            $table->double('shipping_cost');
            $table->double('total_bill');
            $table->string('payment_method')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_letlong')->nullable();
            $table->string('status')->default('pending');
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
