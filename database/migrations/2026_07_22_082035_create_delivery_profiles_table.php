<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_profiles', function (Blueprint $table) {
            $table->id();

            // Connected delivery user
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // Admin approval
            $table->string('approval_status')
                ->default('pending');

            $table->timestamp('approved_at')
                ->nullable();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Delivery availability
            $table->boolean('is_available')
                ->default(false);

            // Optional delivery information
            $table->string('vehicle_type')
                ->nullable();

            $table->string('vehicle_number')
                ->nullable();

            $table->string('driving_license_number')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_profiles');
    }
};