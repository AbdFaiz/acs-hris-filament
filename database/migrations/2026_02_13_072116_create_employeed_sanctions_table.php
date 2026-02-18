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
        Schema::create('employee_sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->enum('level', ['Teguran', 'SP 1', 'SP 2', 'SP 3']);
            $table->string('reason');
            $table->date('issued_date');
            $table->date('expired_date'); // Biasanya berlaku 6 bulan
            $table->string('document_path')->nullable(); // File PDF suratnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeed_sanctions');
    }
};
