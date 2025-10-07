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
       Schema::create('payments', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->unsignedBigInteger('sales_id'); // No foreign key constraint
=======
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
>>>>>>> 90b19b270fc02236ae7bdf212643688af5e04b42
            $table->decimal('amount', 10, 2);
            $table->dateTime('payment_date')->default(now());
            $table->enum('method', ['cash','credit_card','debit_card','online']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
