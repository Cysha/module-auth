<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthCreateRolesTables extends Migration
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
        Schema::create($this->prefix.'roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create($this->prefix.'roleables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->index();
            $table->string('caller_type', 100);
            $table->integer('caller_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->prefix.'roleables');
        Schema::drop($this->prefix.'roles');
    }
}
