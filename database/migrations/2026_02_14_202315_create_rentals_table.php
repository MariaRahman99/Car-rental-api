<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->nullOnDelete();
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->date('actual_return_date')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('status', ['Ongoing', 'Completed', 'Cancelled'])->default('Ongoing');
            $table->string('insurance_option')->nullable();
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->decimal('fuel_level_start', 5, 2)->nullable();
            $table->decimal('fuel_level_end', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
