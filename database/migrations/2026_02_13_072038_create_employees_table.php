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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nik')->unique();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->enum('marital_status', ['single', 'married', 'divorced']);
            $table->date('join_date');
            $table->enum('status', ['permanent', 'contract', 'internship']);
            $table->date('contract_end_date')->nullable();
            $table->date('resign_date')->nullable(); // Poin (b)
            $table->text('resign_reason')->nullable(); // Poin (b)
            $table->string('bpjs_kesehatan_number')->nullable(); // Poin (i) - BPJS
            $table->string('bpjs_ketenagakerjaan_number')->nullable();
            $table->string('npwp_number')->nullable();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('position_id')->constrained();
            $table->foreignId('manager_id')->nullable()->constrained('employees');
            $table->boolean('is_active')->default(true);
            $table->decimal('basic_salary', 15, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
