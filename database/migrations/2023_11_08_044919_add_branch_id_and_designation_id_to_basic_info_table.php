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
        Schema::table('basic_info', function (Blueprint $table) {
            $table->unsignedInteger('branch_id')->after('user_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedInteger('designation_id')->after('department_id')->nullable();
            $table->foreign('designation_id')->references('id')->on('designations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_info', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropColumn('designation_id');
        });
    }
};
