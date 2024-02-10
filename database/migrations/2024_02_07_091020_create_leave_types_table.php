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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Unique code [PL,CL,SL]
            $table->string('name');
            $table->integer('annual_leave_allocation_limit');
            $table->string('taken_frequency')->nullable(); // (monthly, bi-annual, quarterly)
            $table->date('fiscal_year_start');
            $table->date('fiscal_year_end');
            $table->string('fiscal_year');
            $table->boolean('is_active')->default(true);
            $table->boolean('pro_rata_applicable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
