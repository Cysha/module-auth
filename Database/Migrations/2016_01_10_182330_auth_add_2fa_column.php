<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthAdd2faColumn extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('secret_2fa', 16)->nullable()->default(null)->after('remember_token');
            $table->tinyInteger('verified_2fa')->default(0)->after('secret_2fa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verified_2fa');
            $table->dropColumn('secret_2fa');
        });
    }
}
