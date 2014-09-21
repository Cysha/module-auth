<?php

use Illuminate\Database\Migrations\Migration;

class AuthCreateApiAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_auth', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('key');
            $table->timestamp('expires_at');

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
        Schema::drop('api_auth');
    }
}
