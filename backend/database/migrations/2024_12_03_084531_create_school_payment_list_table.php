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
        Schema::create('school_payment_list', function (Blueprint $table) {
            $table->id();
            $table->string('school');
            $table->string('school_emis');
            $table->string('province');
            $table->string('district');
            $table->string('school_bank');
            $table->string('school_bank_branch');
            $table->string('school_bank_branch_code');
            $table->string('school_bank_account');
            $table->string('district_grant_bank');
            $table->string('district_grant_bank_branch');
            $table->string('district_grant_bank_branch_code');
            $table->string('district_grant_bank_account');
            $table->string('district_administration_bank');
            $table->string('district_administration_bank_branch');
            $table->string('district_administration_bank_branch_code');
            $table->string('district_administration_bank_account');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_payment_list');
    }
};
