<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name');
            $table->timestamp('valid_at')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->boolean('active')->unsigned()->nullable()->default('1');
            $table->string('background')->nullable();
            $table->boolean('is_continuous')->unsigned()->nullable()->default('1');
            $table->json('days_week')->nullable();
            $table->timestamps();
        });

        Schema::table('albums_videos', function ($table) {
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
        Schema::dropIfExists('albums_videos');
    }
}
