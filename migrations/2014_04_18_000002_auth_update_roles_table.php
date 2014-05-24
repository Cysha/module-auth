<?php

use Illuminate\Database\Migrations\Migration;

class AuthUpdateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function ($table) {
            $table->string('color', 7)->nullable()->after('name');
            $table->integer('single_user')->default(0)->after('level');
            $table->integer('moderator_id')->after('level');

            //$table->foreign('moderator_id')->references('id')->on('users');
        });

        Schema::table('role_user', function ($table) {
            $table->unique(array('user_id', 'role_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
