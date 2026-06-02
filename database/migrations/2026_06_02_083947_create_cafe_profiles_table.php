<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->default('Cafe A');
            $table->string('legal_name')->nullable();
            $table->string('slogan')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#6F4E37');
            $table->string('secondary_color')->default('#F5E6D3');
            $table->string('accent_color')->default('#2F4F4F');
            $table->string('whatsapp_number')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('email')->nullable();
            $table->text('google_maps_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_profiles');
    }
};