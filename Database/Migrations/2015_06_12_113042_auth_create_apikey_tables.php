<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthCreateApikeyTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('api_auth', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('key');
            $table->string('description');
            $table->timestamp('expires_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('api_auth');
    }
}
