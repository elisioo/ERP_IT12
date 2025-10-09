<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('restock_amount')->default(0);
            $table->string('unit')->default('pcs');
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};