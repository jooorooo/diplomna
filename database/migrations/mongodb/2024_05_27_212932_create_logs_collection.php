<?php

use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsCollection extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('logs', function (Blueprint $collection) {
            $collection->string('event');
            $collection->string('message');
            $collection->string('level');
            $collection->date('timestamp');
            $collection->json('context');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->drop('logs');
    }
}
