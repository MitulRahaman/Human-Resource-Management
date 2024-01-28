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
        Schema::table('nominees', function (Blueprint $table) {
            $table->string('name', 255)->nullable()->change();
            $table->string('relation', 255)->nullable()->change();
            $table->string('phone_number', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->string('name', 255)->nullable(false)->change();
            $table->string('relation', 255)->nullable(false)->change();
            $table->string('phone_number', 255)->nullable(false)->change();
        });
    }
};
