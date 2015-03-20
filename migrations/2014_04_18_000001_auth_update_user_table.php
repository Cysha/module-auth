<?php

use Illuminate\Database\Migrations\Migration;

class AuthUpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('first_name')->nullable()->after('username');
            $table->string('last_name')->nullable()->after('first_name');
            $table->integer('use_nick')->default(0)->after('last_name');
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
