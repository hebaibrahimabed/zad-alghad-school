<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')
                  ->nullable(false)
                  ->constrained('levels')
                  ->cascadeOnDelete();
            $table->string('name')->nullable(false);
            $table->string('academic_year')->nullable(false);
            $table->decimal('price', 8, 2)->nullable(false);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            $table->tinyInteger('min_capacity')->unsigned()->nullable();
            $table->tinyInteger('max_capacity')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
