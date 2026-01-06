<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert JSON slugs to simple strings for categories
        $categories = DB::table('categories')->get();
        
        foreach ($categories as $category) {
            // Check if slug is a JSON string
            $slug = $category->slug;
            
            if (str_starts_with($slug, '{')) {
                // It's a JSON array, decode it
                $slugArray = json_decode($slug, true);
                
                if (is_array($slugArray) && isset($slugArray['en'])) {
                    // Use the English slug
                    $newSlug = $slugArray['en'];
                    
                    // Ensure uniqueness
                    $originalSlug = $newSlug;
                    $counter = 1;
                    while (DB::table('categories')->where('slug', $newSlug)->where('id', '!=', $category->id)->exists()) {
                        $newSlug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update(['slug' => $newSlug]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse, the new format is better
    }
};
