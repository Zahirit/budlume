<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_profiles', function (Blueprint $table) {

            // Whether the partner is currently considered online.
            $table->boolean('is_online')
                ->default(false)
                ->after('is_available');

            // Last activity heartbeat from delivery dashboard.
            $table->dateTime('last_seen_at')
                ->nullable()
                ->after('is_online');

            // Current GPS position.
            $table->decimal('current_latitude', 10, 7)
                ->nullable()
                ->after('last_seen_at');

            $table->decimal('current_longitude', 10, 7)
                ->nullable()
                ->after('current_latitude');

            // When GPS position was last refreshed.
            $table->dateTime('location_updated_at')
                ->nullable()
                ->after('current_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_profiles', function (Blueprint $table) {

            $table->dropColumn([
                'is_online',
                'last_seen_at',
                'current_latitude',
                'current_longitude',
                'location_updated_at',
            ]);
        });
    }
};