<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            // Primary Key
            $table->string('IDNumber', 20)->primary();

            // معلومات الطالب الأساسية
            $table->string('studentName', 20)->nullable(false);
            $table->string('FatherName', 20)->nullable(false);
            $table->string('GrandfatherName', 20)->nullable();
            $table->string('lastName', 20)->nullable(false);

            // معلومات شخصية
            $table->date('dateOfBirth')->nullable(false);
            $table->enum('gender', ['male', 'female'])->nullable(false);
            $table->string('gradeByAge', 20)->nullable();
            $table->string('lastCertificateObtained', 20)->nullable();

            // معلومات التواصل والولي
            $table->string('Parentmobile', 15)->nullable(false);
            $table->string('RelativeGuardian', 20)->nullable();

            // الحالة الصحية واليتم
            $table->enum('healthCondition', ['Healthy', 'disabled', 'injured'])->default('Healthy');
            $table->enum('OrphanStatus', ['orphan', 'not orphan'])->default('not orphan');

            // معلومات التسجيل
            $table->date('registrationDate')->nullable(false);
            $table->string('paymentStatus', 50)->nullable();
            $table->string('RegistrationStatusMinistry', 50)->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });


        // إضافة indexes لتحسين الأداء
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
