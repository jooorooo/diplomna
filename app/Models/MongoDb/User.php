<?php

namespace App\Models\MongoDb;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends \MEloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'username', 'email', 'roles'
    ];
}
