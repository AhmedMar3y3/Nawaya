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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('package_id')->after('workshop_id')->nullable()->constrained('workshop_packages')->onDelete('cascade');
            $table->double('paid_amount')->after('price')->default(0);
            $table->string('transferer_name')->after('paid_amount')->nullable();
            $table->string('notes')->after('transferer_name')->nullable();
            $table->boolean('is_refunded')->after('notes')->default(false);
            $table->string('refund_type')->after('is_refunded')->nullable();
            $table->string('refund_notes')->after('refund_type')->nullable();
            $table->boolean('is_gift_approved')->after('message')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
