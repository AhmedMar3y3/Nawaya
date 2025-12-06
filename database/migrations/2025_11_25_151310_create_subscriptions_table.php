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
            $table->foreignId('package_id')->nullable()->constrained('workshop_packages')->onDelete('cascade');
            $table->double('price')->index();
            $table->double('paid_amount')->default(0)->index();
            $table->string('status')->default(SubscriptionStatus::PENDING->value)->index();
            $table->string('payment_type')->nullable()->index();
            $table->string('invoice_id')->nullable()->index();
            $table->string('invoice_url')->nullable();
            $table->string('transferer_name')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('is_refunded')->default(false);
            $table->string('refund_type')->nullable();
            $table->string('refund_notes')->nullable();

            // Gift flow
            $table->boolean('is_gift')->default(false)->index();
            $table->foreignId('gift_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable()->index();
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_gift_approved')->default(false)->index();
            $table->text('message')->nullable();
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
