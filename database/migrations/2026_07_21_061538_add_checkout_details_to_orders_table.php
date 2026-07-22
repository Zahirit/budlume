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

            /*
            |--------------------------------------------------------------------------
            | Guest / Registered Customer
            |--------------------------------------------------------------------------
            */
            $table->string('customer_type')
                ->default('registered')
                ->after('customer_id');

            /*
            |--------------------------------------------------------------------------
            | Customer Snapshot
            | Keeps the original order information even if profile changes later.
            |--------------------------------------------------------------------------
            */
            $table->string('customer_name')
                ->nullable()
                ->after('customer_type');

            $table->string('customer_email')
                ->nullable()
                ->after('customer_name');

            $table->string('customer_phone', 30)
                ->nullable()
                ->after('customer_email');

            /*
            |--------------------------------------------------------------------------
            | Delivery Address Snapshot
            |--------------------------------------------------------------------------
            */
            $table->string('delivery_address_line_1')
                ->nullable()
                ->after('customer_phone');

            $table->string('delivery_address_line_2')
                ->nullable()
                ->after('delivery_address_line_1');

            $table->string('delivery_city', 100)
                ->nullable()
                ->after('delivery_address_line_2');

            $table->string('delivery_state', 100)
                ->nullable()
                ->after('delivery_city');

            $table->string('delivery_postal_code', 30)
                ->nullable()
                ->after('delivery_state');

            $table->string('delivery_country', 100)
                ->nullable()
                ->after('delivery_postal_code');

            /*
            |--------------------------------------------------------------------------
            | Pricing Snapshot
            |--------------------------------------------------------------------------
            */
            $table->decimal('subtotal', 10, 2)
                ->default(0)
                ->after('delivery_country');

            $table->decimal('discount_percentage', 5, 2)
                ->default(0)
                ->after('subtotal');

            $table->decimal('discount_amount', 10, 2)
                ->default(0)
                ->after('discount_percentage');

            /*
            |--------------------------------------------------------------------------
            | Guest Tracking
            |--------------------------------------------------------------------------
            */
            $table->string('tracking_token', 64)
                ->nullable()
                ->unique()
                ->after('discount_amount');

            /*
            |--------------------------------------------------------------------------
            | Mobile Verification Snapshot
            |--------------------------------------------------------------------------
            */
            $table->timestamp('phone_verified_at')
                ->nullable()
                ->after('tracking_token');

            /*
            |--------------------------------------------------------------------------
            | Allow Guest Orders
            |--------------------------------------------------------------------------
            */
            $table->bigInteger('customer_id')
                        ->unsigned()
                        ->nullable()
                        ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropUnique(['tracking_token']);

            $table->dropColumn([
                'customer_type',
                'customer_name',
                'customer_email',
                'customer_phone',
                'delivery_address_line_1',
                'delivery_address_line_2',
                'delivery_city',
                'delivery_state',
                'delivery_postal_code',
                'delivery_country',
                'subtotal',
                'discount_percentage',
                'discount_amount',
                'tracking_token',
                'phone_verified_at',
            ]);

            $table->unsignedBigInteger('customer_id')
                ->nullable(false)
                ->change();
        });
    }
};