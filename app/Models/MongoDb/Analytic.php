<?php

namespace App\Models\MongoDb;

class Analytic extends \MEloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'analytics';

    protected $fillable = [
        'metric', 'value', 'timestamp', 'additional_info'
    ];

    protected $dates = [
        'timestamp'
    ];
}
