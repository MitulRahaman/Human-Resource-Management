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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('agenda');
            $table->date('date');
            $table->unsignedInteger('place');
            $table->foreign('place')->references('id')->on('meeting_places')->onDelete('cascade');
            $table->bigInteger('start_time');
            $table->bigInteger('end_time');
            $table->string('url')->nullable();
            $table->text('description');
            $table->tinyInteger('status')->default(\Illuminate\Support\Facades\Config::get('variable_constants.meeting_status.pending'));
            $table->string('meeting_minutes')->nullable();
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
        Schema::dropIfExists('meetings');
    }
};
