<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthCreatePermissionsTables extends Migration
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
        Schema::create($this->prefix.'permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 11);
            $table->string('action', 100);
            $table->string('resource_type', 100)->nullable();
            $table->string('readable_name', 100)->nullable();
            $table->integer('resource_id')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::create($this->prefix.'permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
        });

        Schema::create($this->prefix.'permissionables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->string('caller_type', 100);
            $table->integer('caller_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->prefix.'permissionables');
        Schema::drop($this->prefix.'permission_role');
        Schema::drop($this->prefix.'permissions');
    }
}
