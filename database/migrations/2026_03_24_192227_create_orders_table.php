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
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_usd', 10, 2)->default(0);
            $table->decimal('amount_usd', 10, 2);
            $table->string('crypto_currency');
            $table->decimal('crypto_amount', 18, 8);
            $table->decimal('crypto_rate_used', 18, 8);
            $table->string('status')->default('pending');
            $table->string('screenshot_path')->nullable();
            $table->uuid('download_token')->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
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
