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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2)->default(300.00);
            $table->decimal('payment_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_amount', 10, 2)->default(300.00);
            $table->date('first_payment_date')->nullable();
            $table->date('final_payment_date')->nullable();
            $table->enum('status', ['is paid', 'is not paid'])->default('is not paid');
            $table->enum('payment_method', ['cash', 'electronic']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
