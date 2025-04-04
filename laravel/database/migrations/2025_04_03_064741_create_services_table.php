<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // ügyfél egyedi azonosítója
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade'); // ügyfél autójának azonosítója
            $table->integer('log_number'); // Ügyfél és autó szerint egyedi
            $table->string('event'); // esemény típusa
            $table->timestamp('event_time')->nullable(); // esemény időpontja
            $table->string('document_id')->nullable(); // munkalap azonosítója
            $table->timestamps();

            $table->unique(['client_id', 'car_id', 'log_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
