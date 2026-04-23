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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->string('patient_name');
            $table->string('age_gender')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->text('chief_complaints')->nullable();
            $table->text('diagnosis_notes')->nullable();
            $table->text('additional_notes')->nullable();
            $table->json('medicines')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->unique('appointment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
