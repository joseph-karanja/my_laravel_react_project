<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('beneficiary_transaction_status', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('beneficiary_no');
            $table->string('payment_status');
            $table->text('images'); // Storing images as JSON text
            $table->date('date_received');
            $table->double('gps_latitude', 15, 8);
            $table->double('gps_longitude', 15, 8);
            $table->double('gps_altitude');
            $table->timestamp('gps_timestamp')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiary_transaction_status');
    }
};
