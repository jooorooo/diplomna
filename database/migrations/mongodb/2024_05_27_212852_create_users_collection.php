<?php

use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCollection extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('users', function (Blueprint $collection) {
            $collection->string('username')->unique();
            $collection->string('email')->unique();
            $collection->string('password_hash');
            $collection->json('roles');
            $collection->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->drop('users');
    }
}
