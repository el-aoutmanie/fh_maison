<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'completed' status
        DB::statement("ALTER TABLE booked_meetings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'passed', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE booked_meetings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'passed') DEFAULT 'pending'");
    }
};
