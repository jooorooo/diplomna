<?php

namespace App\Models\MongoDb;

class Log extends \MEloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'logs';

    protected $fillable = [
        'level', 'level_name', 'message', 'datetime', 'context', 'extra'
    ];

    protected $dates = [
        'datetime'
    ];
}
