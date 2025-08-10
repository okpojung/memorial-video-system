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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title',250)->nullable()->comment('제목');
            $table->string('name',250)->nullable()->comment('파일명');
            $table->string('format',20)->nullable()->comment('영상포멧');
            $table->bigInteger('size')->nullable()->comment('파일크기');
            $table->string('resolution_w',5)->nullable()->default('1920')->comment('영상해상도가로');
            $table->string('resolution_h',5)->nullable()->default('1080')->comment('영상해상도높이');
            $table->string('playtime_seconds',20)->nullable()->comment('영상시간');
            $table->string('playtime_string',20)->nullable()->comment('영상시간');
            $table->string('mode',10)->nullable()->default('1')->comment('영상스크린모드');
            $table->boolean('repeat')->nullable()->comment('반복유무');
            $table->string('original_name',250)->nullable()->comment('파일저장명');
            $table->string('deceased',50)->nullable()->comment('고인명');
            $table->string('birth',20)->nullable()->comment('출생일');
            $table->string('video_tel',12)->nullable()->comment('전화번호');
            $table->string('death',20)->nullable()->comment('사망일');
            $table->string('video_url',250)->nullable()->comment('파일URL');
            $table->string('thumbnail_url',250)->nullable()->comment('썸네일URL');
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
        Schema::dropIfExists('videos');
    }
};
