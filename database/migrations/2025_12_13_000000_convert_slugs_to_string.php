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
        // Convert products slugs from JSON to string
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            if ($product->slug) {
                $slugData = json_decode($product->slug, true);
                if (is_array($slugData)) {
                    // Use English slug as default, or first available
                    $newSlug = $slugData['en'] ?? reset($slugData) ?? null;
                    if ($newSlug) {
                        DB::table('products')->where('id', $product->id)->update(['slug' => $newSlug]);
                    }
                }
            }
        }

        // Convert categories slugs from JSON to string
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            if ($category->slug) {
                $slugData = json_decode($category->slug, true);
                if (is_array($slugData)) {
                    // Use English slug as default, or first available
                    $newSlug = $slugData['en'] ?? reset($slugData) ?? null;
                    if ($newSlug) {
                        DB::table('categories')->where('id', $category->id)->update(['slug' => $newSlug]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we lose locale-specific slugs
    }
};
