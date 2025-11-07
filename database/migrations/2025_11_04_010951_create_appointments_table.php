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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id'); // Unsigned BigInt PK
            $table->unsignedBigInteger('doctors_id'); // FK
            $table->unsignedBigInteger('patient_id'); // FK
            $table->date('date');
            $table->time('time');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('doctors_id')->references('doctors_id')->on('doctors')->onDelete('cascade');
            $table->foreign('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
