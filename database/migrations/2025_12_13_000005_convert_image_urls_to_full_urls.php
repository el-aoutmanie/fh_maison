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
        // Convert relative image paths to full URLs in images table
        $images = DB::table('images')->get();
        
        foreach ($images as $image) {
            // Check if the url is a relative path (doesn't start with http)
            if (!str_starts_with($image->url, 'http')) {
                $fullUrl = asset('storage/' . $image->url);
                DB::table('images')
                    ->where('id', $image->id)
                    ->update(['url' => $fullUrl]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert full URLs back to relative paths
        $images = DB::table('images')->get();
        
        foreach ($images as $image) {
            // Check if the url is a full URL
            if (str_starts_with($image->url, 'http')) {
                // Extract the relative path
                $relativePath = preg_replace('#^.*/storage/#', '', $image->url);
                DB::table('images')
                    ->where('id', $image->id)
                    ->update(['url' => $relativePath]);
            }
        }
    }
};
