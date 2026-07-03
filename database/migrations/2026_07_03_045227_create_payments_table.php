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

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('midtrans_order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->default('qris');

            $table->unsignedInteger('gross_amount');
            $table->string('currency')->default('IDR');

            $table->string('transaction_status')->default('pending');
            $table->string('fraud_status')->nullable();

            $table->text('qr_url')->nullable();

            $table->json('raw_response')->nullable();
            $table->json('raw_notification')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};