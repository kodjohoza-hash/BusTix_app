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
        Schema::create('displacements', function (Blueprint $table) {
            $table->id();

            $table->string('start_point',50);
            $table->string('destination_point',50);
            $table->decimal('price');
            $table->decimal('distance_km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('displacements');
    }
};
