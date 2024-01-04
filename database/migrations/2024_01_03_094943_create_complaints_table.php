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
        Schema::create('complaints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->unsignedBigInteger('by_whom');
            $table->foreign('by_whom')->references('id')->on('users');
            $table->unsignedBigInteger('against_whom');
            $table->foreign('against_whom')->references('id')->on('users');
            $table->text('description');
            $table->date('complaint_date');
            $table->text('image')->nullable();
            $table->tinyInteger('status')->default(\Illuminate\Support\Facades\Config::get('variable_constants.status.pending'));
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('complaints');
    }
};
