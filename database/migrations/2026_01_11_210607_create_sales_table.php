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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->dateTime('sale_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('cash_amount', 10, 2)->default(0);
            $table->decimal('credit_amount', 10, 2)->default(0);
            $table->enum('payment_type', ['cash', 'credit', 'partial'])->default('cash');
            $table->string('customer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
