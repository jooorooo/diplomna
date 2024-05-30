<?php

namespace App\Models\MongoDb;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitors extends \MEloquent
{
    use HasFactory;

    const CREATED_AT = self::UPDATED_AT;

    protected $collection = 'visitors';

    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip',
        'country',
        'user_agent',
        'record_type',
        'record_id',
    ];

}
