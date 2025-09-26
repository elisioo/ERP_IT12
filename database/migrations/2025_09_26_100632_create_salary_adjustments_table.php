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
         Schema::create('salary_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['bonus', 'deduction'])->default('bonus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_adjustments');
    }
};