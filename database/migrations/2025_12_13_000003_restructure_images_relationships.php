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
        // Add service_id to images table
        if (!Schema::hasColumn('images', 'service_id')) {
            Schema::table('images', function (Blueprint $table) {
                $table->foreignId('service_id')->nullable()->after('variant_id')->constrained()->onDelete('cascade');
            });
        }

        // Migrate existing product images to images table if images column exists
        if (Schema::hasColumn('products', 'images')) {
            $products = DB::table('products')->whereNotNull('images')->get();
            foreach ($products as $product) {
                $images = json_decode($product->images, true);
                if (is_array($images)) {
                    foreach ($images as $index => $imagePath) {
                        DB::table('images')->insert([
                            'product_id' => $product->id,
                            'name' => basename($imagePath),
                            'url' => $imagePath,
                            'order' => $index,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Drop images column from products table
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('images');
            });
        }

        // Drop image and image_url from services if they exist
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('services', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('images')->nullable();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();
        });
    }
};
