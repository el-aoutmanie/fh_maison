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
        // Add tracking_number and notes to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('tracking_number');
            }
        });

        // Add shipping_price to products
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'shipping_price')) {
                $table->decimal('shipping_price', 10, 2)->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'notes']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shipping_price');
        });
    }
};
