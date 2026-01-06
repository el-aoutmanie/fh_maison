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
        Schema::table('services', function (Blueprint $table) {
            // Rename 'name' to 'title'
            $table->renameColumn('name', 'title');
            
            // Add missing columns
            $table->json('slug')->after('title');
            $table->decimal('price', 10, 2)->default(0)->after('description');
            $table->integer('duration')->nullable()->after('price')->comment('Duration in minutes');
            $table->json('features')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn(['slug', 'price', 'duration', 'features']);
            
            // Rename 'title' back to 'name'
            $table->renameColumn('title', 'name');
        });
    }
};
