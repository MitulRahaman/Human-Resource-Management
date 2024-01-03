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
        Schema::create('warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->unsignedBigInteger('warning_by');
            $table->foreign('warning_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('warning_to');
            $table->foreign('warning_to')->references('id')->on('users')->onDelete('cascade');
            $table->date('date');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(\Illuminate\Support\Facades\Config::get('variable_constants.warning_status.pending'));
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
        Schema::dropIfExists('warnings');
    }
};
