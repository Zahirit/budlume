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
            | Assigned Delivery Partner
            |--------------------------------------------------------------------------
            |
            | References the delivery man's user account.
            | Only approved delivery users should be assigned by application logic.
            |
            */
            $table->foreignId('delivery_man_id')
                ->nullable()
                ->after('phone_verified_at')
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Delivery Status
            |--------------------------------------------------------------------------
            */
            $table->string('delivery_status')
                ->default('unassigned')
                ->after('delivery_man_id');

            /*
            |--------------------------------------------------------------------------
            | Delivery Timeline
            |--------------------------------------------------------------------------
            */
            $table->timestamp('assigned_at')
                ->nullable()
                ->after('delivery_status');

            $table->timestamp('accepted_at')
                ->nullable()
                ->after('assigned_at');

            $table->timestamp('picked_up_at')
                ->nullable()
                ->after('accepted_at');

            $table->timestamp('out_for_delivery_at')
                ->nullable()
                ->after('picked_up_at');

            $table->timestamp('delivered_at')
                ->nullable()
                ->after('out_for_delivery_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            /*
            | Drop foreign key first.
            */
            $table->dropForeign([
                'delivery_man_id'
            ]);

            $table->dropColumn([
                'delivery_man_id',
                'delivery_status',
                'assigned_at',
                'accepted_at',
                'picked_up_at',
                'out_for_delivery_at',
                'delivered_at',
            ]);
        });
    }
};