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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->restrictOnDelete('');
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete('');
            $table->foreignId('ticket_reservation_id')->constrained('ticket_reservations')->restrictOnDelete('');
            $table->decimal('seat_number', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
