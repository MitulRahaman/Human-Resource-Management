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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('url')->nullable();
            $table->text('icon')->nullable();
            $table->text('description')->nullable();
            $table->integer('menu_order')->nullable();
            $table->unsignedInteger('parent_menu')->nullable();
            $table->foreign('parent_menu')->references('id')->on('menus')->onDelete('cascade');
            $table->tinyInteger('status')->default(\Illuminate\Support\Facades\Config::get('variable_constants.activation.active'));
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
        Schema::dropIfExists('menus');
    }
};
