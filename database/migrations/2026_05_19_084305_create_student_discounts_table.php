<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')
                  ->nullable(false)
                  ->constrained('registrations')
                  ->cascadeOnDelete();
            $table->foreignId('discount_id')
                  ->nullable(false)
                  ->constrained('discounts')
                  ->cascadeOnDelete();
            $table->decimal('applied_value', 8, 2)->nullable(false);
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('applied_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_discounts');
    }
};
