<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->integer('company_id')->unsigned();
            $table->timestamp('birthday')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('admin')->unsigned()->nullable()->default('0');
            $table->boolean('active')->unsigned()->nullable()->default('0');
            $table->string('token_active')->nullable();
            $table->boolean('master')->unsigned()->nullable()->default('0');
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
}
