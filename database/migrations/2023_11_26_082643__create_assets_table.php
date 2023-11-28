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
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedInteger('type_id');
            $table->foreign('type_id')->references('id')->on('asset_types')->onDelete('cascade');
            $table->integer('sl_no')->nullable();
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->text('specification')->nullable();
            $table->string('purchase_at')->nullable();
            $table->string('purchase_by')->nullable();
            $table->decimal('purchase_price')->nullable();
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
        Schema::dropIfExists('assets');
    }
};
