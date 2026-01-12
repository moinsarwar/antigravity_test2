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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_invoice_number')->unique();
            $table->foreignId('original_sale_id')->constrained('sales')->onDelete('cascade');
            $table->dateTime('return_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('return_type', ['return', 'exchange'])->default('return');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
