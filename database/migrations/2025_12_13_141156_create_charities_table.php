<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Subscription\SubscriptionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('workshop_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained('workshop_packages')->onDelete('cascade');
            $table->integer('number_of_seats')->index();
            $table->integer('used_seats')->default(0)->index();
            $table->double('price')->index();
            $table->string('status')->default(SubscriptionStatus::PENDING->value)->index();
            $table->string('payment_type')->nullable()->index();
            $table->string('invoice_id')->nullable()->index();
            $table->string('invoice_url')->nullable();
            $table->integer('refunded_seats')->default(0);
            $table->integer('user_balance')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charities');
    }
};
