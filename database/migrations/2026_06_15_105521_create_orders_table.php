<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code')->unique();

            $table->foreignId('table_qr_code_id')
                ->constrained('table_qr_codes')
                ->cascadeOnDelete();

            $table->foreignId('restaurant_table_id')
                ->constrained('restaurant_tables')
                ->cascadeOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->text('customer_note')->nullable();

            $table->unsignedInteger('total_amount')->default(0);

            $table->string('status')->default('pending');
            $table->string('payment_status')->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};