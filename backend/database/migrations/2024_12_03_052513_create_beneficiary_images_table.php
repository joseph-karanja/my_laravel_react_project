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
        Schema::create('beneficiary_images', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_number');
            $table->string('image_id');
            $table->text('image_url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiary_images');
    }
};
