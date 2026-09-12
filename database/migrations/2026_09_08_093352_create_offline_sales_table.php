<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offline_sales', function (Blueprint $table) {
            $table->id();

            // Human-readable unique ID, e.g. OFF-000001
            $table->string('sale_id')->unique();

            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();

            $table->date('sale_date');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // cash, bkash, nagad, bank, other
            $table->string('payment_method');

            // paid, unpaid, partial
            $table->string('payment_status')->default('paid');

            $table->string('payment_reference')->nullable();

            $table->text('notes')->nullable();

            // completed, cancelled
            $table->string('status')->default('completed');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index('sale_date');
            $table->index('payment_method');
            $table->index('payment_status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_sales');
    }
};