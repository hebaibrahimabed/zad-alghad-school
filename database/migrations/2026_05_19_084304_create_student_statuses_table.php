<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')
                  ->nullable(false)
                  ->constrained('registrations')
                  ->cascadeOnDelete();
            $table->enum('status', ['active', 'suspended', 'withdrawn'])->nullable(false);
            $table->date('status_date')->nullable(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_statuses');
    }
};
