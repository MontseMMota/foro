<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('social_links')->nullable();
            $table->string('profile_banner')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'birth_date',
                'description',
                'avatar',
                'location',
                'website',
                'phone',
                'is_verified',
                'social_links',
                'profile_banner'
            ]);
        });
    }
}