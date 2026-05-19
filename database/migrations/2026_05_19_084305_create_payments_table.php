<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')
                  ->nullable(false)
                  ->constrained('registrations')
                  ->cascadeOnDelete();
            $table->decimal('amount_due_month', 8, 2)->nullable(false);
            $table->decimal('total_outstanding', 8, 2)->nullable(false);
            $table->decimal('amount_paid', 8, 2)->nullable(false);
            $table->date('due_date')->nullable(false);
            $table->timestamp('paid_at')->nullable();
            $table->enum('payment_method', ['cash', 'app'])->nullable();
            $table->enum('status', ['pending', 'partial', 'paid'])->nullable(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
