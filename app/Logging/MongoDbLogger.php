<?php

namespace App\Logging;

use Monolog\Logger;

class MongoDbLogger
{
    public function __invoke(array $log)
    {
        return new Logger('MongoDb', [
            new MongoDbLogHandler(),
        ]);
    }
}
