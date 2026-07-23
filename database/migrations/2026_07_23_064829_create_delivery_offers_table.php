<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_offers', function (Blueprint $table) {
            $table->id();

            // Order being offered
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Delivery partner receiving this offer
            $table->foreignId('delivery_man_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Offer Status
            |--------------------------------------------------------------------------
            | pending  = waiting for response
            | accepted = delivery partner accepted
            | rejected = delivery partner rejected
            | expired  = no response within 30 seconds
            */
            $table->string('status')
                ->default('pending');

            // Position in the candidate sequence
            $table->unsignedInteger('offer_sequence')
                ->default(1);

            // Distance from pickup/store when selected
            $table->decimal(
                'distance_km',
                8,
                2
            )->nullable();

            // Rating snapshot when selected
            $table->decimal(
                'rating_snapshot',
                3,
                2
            )->nullable();

            // Offer timing
            $table->dateTime('offered_at');

            $table->dateTime('expires_at');
            
            $table->dateTime('responded_at')
                ->nullable();

            $table->timestamps();

            // Useful for finding active offers quickly
            $table->index([
                'order_id',
                'status',
            ]);

            $table->index([
                'delivery_man_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_offers');
    }
};