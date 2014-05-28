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
            $table->boolean('single_user')->default(false)->after('level');

        });

        Schema::table('role_user', function ($table) {
            $table->boolean('is_moderator')->default(false)->after('role_id');

            $table->unique(array('user_id', 'role_id'));
        });

        Schema::table('permissions', function ($table) {
            $table->text('class_namespace')->after('description');
        });

        Schema::table('permission_role', function ($table) {
            $table->integer('resource_id')->after('role_id');
            $table->enum('value', [0, 1, -1])->default(0)->after('resource_id');
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
