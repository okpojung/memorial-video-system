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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->nullable()->comment('고객등급');
            $table->string('tel',12)->nullable()->comment('고객명');
            $table->string('email',250)->nullable()->comment('휴대폰번호');
            $table->string('zone_code',45)->nullable()->comment('이메일');
            $table->string('sido',45)->nullable()->comment('우편번호');
            $table->string('sigungu',45)->nullable()->comment('시도');
            $table->string('bname',45)->nullable()->comment('시군구');
            $table->string('roadname',250)->nullable()->comment('동명');
            $table->string('road_address',250)->nullable()->comment('도로');
            $table->string('jibun_address',250)->nullable()->comment('도로명주소');
            $table->string('detail_address',250)->nullable()->comment('지번주소');
            $table->string('latitude',50)->nullable()->comment('상세주소');
            $table->string('longitude',50)->nullable()->comment('위도');
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
        Schema::dropIfExists('customers');
    }
};
