<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthCreateUserTable extends Migration
{
    public function __construct()
    {
        // Get the prefix
        $this->prefix = config('cms.auth.config.table-prefix', 'auth_');
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->prefix.'users', function (Blueprint $table) {
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
     */
    public function down()
    {
        Schema::drop($this->prefix.'users');
    }
}
