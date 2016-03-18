<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthAddPassExpireColumn extends Migration
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
        Schema::table($this->prefix.'users', function (Blueprint $table) {
            $table->timestamp('pass_expires_on')->nullable()->default(null)->after('use_nick');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->prefix.'users', function (Blueprint $table) {
            $table->dropColumn('pass_expires_on');
        });
    }
}
