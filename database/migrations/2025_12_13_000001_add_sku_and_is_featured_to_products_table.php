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
        // Drop sku column if it exists
        if (Schema::hasColumn('products', 'sku')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sku');
            });
        }

        // Add columns
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('slug');
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
        });

        // Generate unique SKUs for existing products
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['sku' => 'SKU-' . strtoupper(uniqid())]);
        }

        // Now make SKU unique
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'is_featured']);
        });
    }
};
