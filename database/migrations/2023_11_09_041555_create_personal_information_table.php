<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('father_name', 255);
            $table->string('mother_name', 255);
            $table->string('nid', 255);
            $table->string('birth_certificate')->nullable();
            $table->string('passport_no')->nullable();
            $table->tinyInteger('gender')->default(\Illuminate\Support\Facades\Config::get('variable_constants.gender.male'));
            $table->tinyInteger('religion');
            $table->tinyInteger('blood_group');
            $table->date('dob');
            $table->tinyInteger('marital_status')->default(\Illuminate\Support\Facades\Config::get('variable_constants.marital_status.unmarried'));
            $table->unsignedInteger('no_of_children')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_info');
    }
};
