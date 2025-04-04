<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // ügyfél egyedi azonosítója 
            $table->integer('car_id'); // Ügyfelenként egyedi azonosító
            $table->string('type'); // autó típusa
            $table->timestamp('registered')->nullable(); // regisztrálás időpontja
            $table->boolean('ownbrand')->default(0); // értéke 1 ha saját márkás, értéke 0 ha nem saját márkás
            $table->integer('accidents')->default(0); // balesetek száma
            $table->timestamps();

            $table->unique(['client_id', 'car_id']); // Ügyfelenként egyedi autóazonosító
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

