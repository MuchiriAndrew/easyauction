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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->enum('style', ['saloon', 'suv', 'hatchback', 'coupe', 'convertible', 'estate', 'mpv', 'pickup', 'van', 'minibus', 'campervan', 'limousine', 'other']);
            $table->enum('transmission', ['automatic', 'manual']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->string('color');
            $table->integer('mileage');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('vin');
            $table->text('description');
            $table->string('photo_path');
            $table->enum('status', ['approved', 'pending', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Drop the table
        Schema::dropIfExists('cars');

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
