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
        Schema::create('employee_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('evaluator_id')->constrained('employees'); // Atasan yang menilai
            $table->date('evaluation_date');
            $table->json('scores'); // Simpan poin penilaian (KPI, Attitude, dll)
            $table->text('recommendation'); // Perpanjang / Putus Kontrak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_appraisals');
    }
};
