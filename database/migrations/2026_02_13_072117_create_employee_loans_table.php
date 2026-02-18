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
        Schema::create('employee_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->integer('installment_count'); // Berapa bulan cicilan
            $table->decimal('monthly_deduction', 15, 2); // Potongan / bulan
            $table->integer('remaining_installments');
            $table->enum('status', ['pending', 'approved', 'rejected', 'ongoing', 'paid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_loans');
    }
};
