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
        Schema::create('workshop_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('workshop_transfer_from')->constrained('workshops')->onDelete('cascade');
            $table->foreignId('workshop_transfer_to')->constrained('workshops')->onDelete('cascade');
            $table->double('old_price');
            $table->double('new_price');
            $table->double('paid_amount')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_transfers');
    }
};
