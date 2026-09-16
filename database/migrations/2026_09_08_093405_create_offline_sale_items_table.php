<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offline_sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('offline_sale_id')
                ->constrained('offline_sales')
                ->cascadeOnDelete();

            // nullOnDelete: keep sale history even if product/variant deleted
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // Snapshot fields — historical accuracy
            $table->string('product_name');
            $table->string('variant_name')->nullable();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_sale_items');
    }
};