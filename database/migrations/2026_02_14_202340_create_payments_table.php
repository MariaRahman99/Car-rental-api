<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained('car_reservations')
                ->nullOnDelete();

            $table->foreignId('rental_id')
                ->nullable()
                ->constrained('rentals')
                ->nullOnDelete();

            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Debit Card', 'Online']);
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};