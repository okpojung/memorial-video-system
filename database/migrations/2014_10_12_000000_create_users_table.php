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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->unsigned()->comment('등급구분');
            $table->string('reg_id',10)->unique()->comment('아이디');
            $table->string('password')->comment('비밀번호');
            $table->string('name',10)->nullable()->comment('이름');
            $table->string('tel',12)->nullable()->comment('핸드폰번호');
            $table->string('email',100)->nullable()->comment('이메일');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
