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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage');
            $table->decimal('shipping_amount', 10, 2)->default(0)->after('discount_amount');
            $table->string('shipping_name')->nullable()->after('shipping_amount');
            $table->text('shipping_address')->nullable()->after('shipping_name');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_zip')->nullable()->after('shipping_state');
            $table->string('shipping_country')->nullable()->after('shipping_zip');
            $table->string('shipping_phone')->nullable()->after('shipping_country');
            $table->string('payment_method')->default('cod')->after('shipping_phone');
            $table->string('payment_status')->default('pending')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'discount_amount', 
                'shipping_amount',
                'shipping_name',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_zip',
                'shipping_country',
                'shipping_phone',
                'payment_method',
                'payment_status'
            ]);
        });
    }
};
