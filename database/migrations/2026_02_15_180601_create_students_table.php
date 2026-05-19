<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('parents')
                  ->nullOnDelete();

            $table->string('IDNumber', 20)->unique()->nullable(false);
            $table->string('studentName', 20)->nullable(false);
            $table->string('FatherName', 20)->nullable(false);
            $table->string('GrandfatherName', 20)->nullable();
            $table->string('lastName', 20)->nullable(false);
            $table->date('dateOfBirth')->nullable(false);
            $table->enum('gender', ['male', 'female'])->nullable(false);
            $table->string('gradeByAge', 20)->nullable();
            $table->string('lastCertificateObtained', 20)->nullable();
            $table->string('Parentmobile', 15)->nullable(false);
            $table->string('RelativeGuardian', 20)->nullable();
            $table->enum('healthCondition', ['Healthy', 'disabled', 'injured'])->default('Healthy');
            $table->date('registrationDate')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index('studentName');
            $table->index('Parentmobile');
            $table->index('registrationDate');
            $table->index('gender');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
