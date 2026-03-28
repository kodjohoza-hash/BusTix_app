<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('telephone');
            $table->string('license_number')->unique();
            $table->foreignId('bus_id')->nullable()->constrained('buses')->onDelete('set null');
            $table->enum('status', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('drivers');
    }
};