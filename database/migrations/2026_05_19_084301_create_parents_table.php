<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 20)->nullable(false);
            $table->string('second_name', 20)->nullable(false);
            $table->string('third_name', 20)->nullable(false);
            $table->enum('gender', ['male', 'female'])->nullable(false);
            $table->date('birth_date')->nullable();
            $table->string('phone', 15)->nullable(false);
            $table->string('national_id', 20)->unique()->nullable();
            $table->enum('relation', [
                'father', 'mother', 'brother', 'sister',
                'uncle', 'aunt', 'grandfather', 'grandmother', 'other'
            ])->nullable(false);
            $table->string('address')->nullable();
            $table->enum('housing_status', ['owned', 'rented', 'tent', 'displaced'])->nullable();
            $table->string('work', 50)->nullable();
            $table->enum('orphan_status_student', [
                'not_orphan', 'father', 'mother', 'both'
            ])->default('not_orphan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
