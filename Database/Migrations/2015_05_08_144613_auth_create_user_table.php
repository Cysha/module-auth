<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AuthCreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->text('avatar');

            $table->string('password', 60)->nullable();
            $table->string('salt', 32);
            $table->rememberToken();

            $table->integer('use_nick')->default(0);

            $table->timestamp('last_logged_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
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
        Schema::drop('users');
    }
}
