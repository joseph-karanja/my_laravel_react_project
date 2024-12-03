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
        Schema::create('beneficiary_payment_batch', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('current_grade');
            $table->string('district');
            $table->string('phone_number_guardian');
            $table->string('nrc_guardian');
            $table->string('school');
            $table->decimal('school_fees', 8, 2);
            $table->decimal('education_grant', 8, 2);
            $table->string('payment_model');
            $table->string('payment_period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_payment_batch');
    }
};
