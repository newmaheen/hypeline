<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // SIGNED: negative = stock went out, positive = stock came back in
            $table->integer('quantity');

            // online_sale, offline_sale, online_cancel, offline_cancel,
            // stock_addition, stock_adjustment
            $table->string('type')->index();

            // Human-readable reference, e.g. "OFF-000001"
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->string('notes')->nullable();

            $table->timestamps();

            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};