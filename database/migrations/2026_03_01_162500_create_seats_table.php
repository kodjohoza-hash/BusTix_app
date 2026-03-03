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
            $table->foreignId('bus_id')->constrained('')->restrictOnDelete('');
            $table->foreignId('customer_id')->constrained('')->restrictOnDelete('');
            $table->foreignId('reservation_id')->constrained('')->restrictOnDelete('');
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
