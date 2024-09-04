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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('section_id')->constrained('sections')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['class', 'exam', 'activity']);

            // weekly schedules
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'])->nullable();
            // examentions or activity
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
