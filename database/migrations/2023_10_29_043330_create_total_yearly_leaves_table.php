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
        Schema::create('total_yearly_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('leave_type_id');
            $table->foreign('leave_type_id')->references('id')->on('leave_types');
            $table->integer('year');
            $table->integer('total_leaves');
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
        Schema::dropIfExists('total_yearly_leaves');
    }
};
