<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsVideosVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_videos_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_video_id')->unsigned();
            $table->integer('video_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('albums_videos_videos', function ($table) {
            $table->foreign('album_video_id')->references('id')->on('albums_videos')->onDelete('cascade');
        });

        Schema::table('albums_videos_videos', function ($table) {
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums_videos_videos');
    }
}
