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
        // Convert relative image paths to full URLs for categories
        $categories = DB::table('categories')->whereNotNull('image_url')->get();
        
        foreach ($categories as $category) {
            // Check if the image_url is a relative path (doesn't start with http)
            if (!str_starts_with($category->image_url, 'http')) {
                $fullUrl = asset('storage/' . $category->image_url);
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['image_url' => $fullUrl]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert full URLs back to relative paths
        $categories = DB::table('categories')->whereNotNull('image_url')->get();
        
        foreach ($categories as $category) {
            // Check if the image_url is a full URL
            if (str_starts_with($category->image_url, 'http')) {
                // Extract the relative path
                $relativePath = preg_replace('#^.*/storage/#', '', $category->image_url);
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['image_url' => $relativePath]);
            }
        }
    }
};
