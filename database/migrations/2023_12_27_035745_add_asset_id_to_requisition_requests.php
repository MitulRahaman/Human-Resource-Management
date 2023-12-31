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
        Schema::table('requisition_requests', function (Blueprint $table) {
            $table->unsignedInteger('asset_id')->nullable()->after('asset_type_id');;
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_requests', function (Blueprint $table) {
            $table->dropColumn('asset_id');
        });
    }
};
