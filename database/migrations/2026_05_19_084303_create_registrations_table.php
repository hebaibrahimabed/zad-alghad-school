<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->nullable(false)
                  ->constrained('students')
                  ->cascadeOnDelete();
            $table->foreignId('class_id')
                  ->nullable(false)
                  ->constrained('classes')
                  ->cascadeOnDelete();
            $table->date('registration_date')->nullable(false);
            $table->enum('ministry_registration', ['pending', 'registered', 'exempt'])->nullable(false);
            $table->enum('current_status', ['active', 'suspended', 'withdrawn'])->nullable(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
