<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_table_id')->constrained('restaurant_tables')->cascadeOnDelete();
            $table->string('qr_token')->unique();
            $table->text('qr_url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('restaurant_table_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_qr_codes');
    }
};