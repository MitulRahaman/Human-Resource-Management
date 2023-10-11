<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 255)->unique();
            $table->string('full_name', 255);
            $table->string('nick_name', 255);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->tinyInteger('is_super_user')->default(Config::get('variable_constants.check.no'));
            $table->tinyInteger('is_registration_complete')->default(Config::get('variable_constants.check.yes'));
            $table->tinyInteger('is_password_changed')->default(Config::get('variable_constants.check.no'));
            $table->tinyInteger('status')->default(Config::get('variable_constants.check.no'));
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
