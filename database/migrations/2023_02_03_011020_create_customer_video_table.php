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
        Schema::create('customer_video', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->foreignId('customer_id')->unsigned()->comment('고객아이디')
                ->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('video_id')->unsigned()->comment('비디오아이디')
                ->references('id')->on('videos')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_video');
    }
};
