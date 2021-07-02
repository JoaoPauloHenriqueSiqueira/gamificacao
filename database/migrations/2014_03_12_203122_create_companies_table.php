<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->unsigned()->nullable(true)->default('1');
            $table->boolean('chat')->unsigned()->nullable(true)->default('1');
            $table->string('phone')->nullable(true);
            $table->string('logo')->nullable(true);
            $table->string('token_screen')->nullable(true);
            $table->string('background_default')->nullable(true);
            $table->string('password_default')->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('cnpj')->nullable(true);
            $table->string('postalCode')->nullable(true); //
            $table->string('district')->nullable(true); //bairro
            $table->string('street')->nullable(true);
            $table->string('number')->nullable(true); 
            $table->string('city')->nullable(true); //
            $table->string('state')->nullable(true); //
            $table->string('country')->nullable(true); //

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
