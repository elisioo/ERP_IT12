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
        // Column already exists in original migration - no action needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Column exists in original migration - no action needed
    }
};
