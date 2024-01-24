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
        Schema::table('banking_info', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['bank_id']);

            $table->unsignedBigInteger('user_id')->nullable()->change()->constrained('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('bank_id')->nullable()->change()->constrained('banks');
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->string('account_name', 255)->nullable()->change();
            $table->string('account_number', 255)->nullable()->change();
            $table->string('branch', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banking_info', function (Blueprint $table) {
            Schema::dropIfExists('user_id');
            Schema::dropIfExists('bank_id');
            Schema::dropIfExists('account_name');
            Schema::dropIfExists('account_number');
            Schema::dropIfExists('branch');
        });
    }

};
