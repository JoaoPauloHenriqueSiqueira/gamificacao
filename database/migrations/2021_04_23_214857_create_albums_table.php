<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name');
            $table->timestamp('valid_at')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->boolean('active')->unsigned()->nullable()->default('1');
            $table->string('background')->nullable();
            $table->integer('duration_frames')->unsigned()->nullable()->default(20);
            $table->boolean('is_continuous')->unsigned()->nullable()->default('1');
            $table->json('days_week')->nullable();
            $table->timestamps();
        });

        Schema::table('albums', function ($table) {
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
        Schema::dropIfExists('albums');
    }
}
