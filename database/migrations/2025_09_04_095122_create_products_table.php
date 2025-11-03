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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('images')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 0)->default(0);
            $table->integer('stock')->default(0);
            $table->decimal('weight', 8, 0)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            $table->index(['category_id', 'is_active'], 'idx_products_category_active');
            $table->index(['slug'], 'idx_products_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
