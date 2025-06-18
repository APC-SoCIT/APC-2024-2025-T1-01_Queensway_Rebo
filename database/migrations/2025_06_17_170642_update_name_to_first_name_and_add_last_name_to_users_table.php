<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNameToFirstNameAndAddLastNameToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name'); // rename name to first_name
            $table->string('last_name')->after('first_name'); // add last_name after first_name
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name'); // revert rename
            $table->dropColumn('last_name'); // remove last_name
        });
    }
}
