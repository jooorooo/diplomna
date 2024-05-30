<?php

namespace App\Logging;

use App\Models\MongoDb\Log;
use Monolog\Handler\AbstractProcessingHandler;

class MongoDbLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $record['timestamp'] = now();
        Log::create($record);
    }
}
