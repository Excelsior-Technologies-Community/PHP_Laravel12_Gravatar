<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gravatars', function (Blueprint $table) {
            $table->string('rating')->default('g')->after('size');
            $table->string('default_image')->default('identicon')->after('rating');
            $table->boolean('is_favorite')->default(false)->after('default_image');
            $table->boolean('has_real_gravatar')->nullable()->after('is_favorite');
            $table->timestamp('gravatar_checked_at')->nullable()->after('has_real_gravatar');
        });
    }

    public function down()
    {
        Schema::table('gravatars', function (Blueprint $table) {
            $table->dropColumn(['rating', 'default_image', 'is_favorite', 'has_real_gravatar', 'gravatar_checked_at']);
        });
    }
};