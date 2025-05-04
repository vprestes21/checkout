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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('logo_url')->nullable();
            $table->integer('logo_width')->default(200);
            $table->integer('logo_height')->default(200);
            $table->json('payment_methods'); // ["pix", "card"]
            $table->string('template')->default('modern');
            $table->string('primary_color', 7)->default('#3490dc');
            $table->string('secondary_color', 7)->default('#38c172');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
