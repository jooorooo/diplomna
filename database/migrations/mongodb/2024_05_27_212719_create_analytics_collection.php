<?php

use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsCollection extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('analytics', function (Blueprint $collection) {
            $collection->string('metric');
            $collection->float('value');
            $collection->date('timestamp');
            $collection->json('additional_info');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->drop('analytics');
    }
}
