<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_profiles', function (Blueprint $table) {

            // Clear photo of Driving License
            $table->string('driving_license_photo')
                ->nullable()
                ->after('driving_license_number');

            // SIN will be encrypted/decrypted by Laravel model
            $table->text('sin_number')
                ->nullable()
                ->after('driving_license_photo');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'driving_license_photo',
                'sin_number',
            ]);
        });
    }
};