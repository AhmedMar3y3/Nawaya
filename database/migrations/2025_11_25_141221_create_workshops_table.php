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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('teacher');
            $table->double('teacher_per')->default(0);
            $table->text('description')->nullable();
            $table->text('subject_of_discussion');
            $table->boolean('is_active')->default(true);
            $table->string('type');
            
            // Online & Onsite & Onsite_Online cases
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('online_link')->nullable();

            // Onsite & Onsite_Online cases
            $table->string('city')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('hotel')->nullable();
            $table->string('hall')->nullable();

            // Recorded cases
            $table->double('recording_price')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
