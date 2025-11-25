<?php

use App\Enums\Subscription\SubscriptionStatus;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('workshop_id')->constrained()->onDelete('cascade');
            $table->double('price');
            $table->string('status')->default(SubscriptionStatus::PENDING->value)->index();
            $table->string('payment_type')->index();
            $table->string('invoice_id')->nullable();
            $table->string('invoice_url')->nullable();

            // Gift flow
            $table->boolean('is_gift')->default(false);
            $table->foreignId('gift_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable()->index();
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
