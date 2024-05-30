<?php

namespace App\Console\Commands;

use App\Models\MongoDb\Visitors;
use Illuminate\Console\Command;
use Jenssegers\Mongodb\Collection;
use Jenssegers\Mongodb\Connection;

abstract class AbstractVisitors extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->generate();
    }

    protected function getMongoConnection(): Connection
    {
        $model = new Visitors();
        return $model->getConnection();
    }

    /**
     * @return Collection|\MongoDB\Collection
     */
    protected function getCollection($name): Collection
    {
        return $this->getMongoConnection()->getCollection($name);
    }

    /**
     * @return Collection|\MongoDB\Collection
     */
    protected function getVisitorsCollection(): Collection
    {
        return $this->getCollection('visitors');
    }

    abstract protected function generate();
}
