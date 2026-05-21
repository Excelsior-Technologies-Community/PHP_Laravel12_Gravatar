<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gravatars', function (Blueprint $table) {
            $table->integer('size')->default(200)->after('avatar');
        });
    }

    public function down()
    {
        Schema::table('gravatars', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
};