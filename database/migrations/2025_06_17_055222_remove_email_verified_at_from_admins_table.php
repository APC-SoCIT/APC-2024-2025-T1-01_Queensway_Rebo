<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEmailVerifiedAtFromAdminsTable extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
        });
    }
}
