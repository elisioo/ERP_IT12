<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
       Schema::create('upcoming_payments', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD:database/migrations/2025_09_26_100610_create_salaries_table.php
            $table->unsignedBigInteger('employee_id');
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->date('salary_date');
=======
            $table->string('title');
            $table->string('icon')->nullable();
            $table->date('date');
            $table->enum('status', ['pending', 'paid'])->default('pending');
>>>>>>> 90b19b270fc02236ae7bdf212643688af5e04b42:database/migrations/2025_10_06_013809_creaet_upcoming_payments_table.php
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
