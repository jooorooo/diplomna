<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\MongoDb\Visitors;
use App\Models\MongoDb\Analytic;

class GenerateAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate analytics metrics from visitors data';

    protected $types = [
        'product',
        'category',
        'brand',
        'collection',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Analytic::truncate();

        try {
            // Пример за генериране на брой уникални посетители
            $uniqueVisitors = Visitors::raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            '_id' => [
                                'ip' => '$ip',
                                'day' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d',
                                        'date' => '$updated_at'
                                    ]
                                ]
                            ],
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => [
                                'day' => '$_id.day'
                            ],
                            'count' => [
                                '$sum' => 1
                            ]
                        ]
                    ],
                    [
                        '$project' => [
                            'day' => '$_id.day',
                            'count' => 1
                        ]
                    ]
                ]);
            })->toArray();

            foreach($uniqueVisitors as $visit) {
                Analytic::create([
                    'metric' => 'unique_visitors',
                    'value' => $visit['count'],
                    'timestamp' => Carbon::parse($visit['day']),
                    'additional_info' => [
                        'description' => 'Total number of unique visitors'
                    ]
                ]);
            }

            // Пример за генериране на брой посещения по държава
            $visitsByCountry = Visitors::raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            '_id' => [
                                'country' => '$country',
                                'day' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d',
                                        'date' => '$updated_at'
                                    ]
                                ]
                            ],
                            'count' => [
                                '$sum' => 1
                            ],
                        ]
                    ],
                    [
                        '$project' => [
                            'day' => '$_id.day',
                            'country' => '$_id.country',
                            'count' => 1
                        ]
                    ]
                ]);
            })->toArray();

            foreach ($visitsByCountry as $visit) {
                Analytic::create([
                    'metric' => 'visits_by_country',
                    'value' => $visit['count'],
                    'timestamp' => Carbon::parse($visit['day']),
                    'additional_info' => [
                        'country' => $visit['country'],
                        'description' => 'Number of visits by country'
                    ]
                ]);
            }

            // Генериране на брой посещения по ден
            $visitsByDay = Visitors::raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            '_id' => [
                                'day' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d',
                                        'date' => '$updated_at'
                                    ]
                                ]
                            ],
                            'count' => [
                                '$sum' => 1
                            ]
                        ]
                    ],
                    [
                        '$project' => [
                            'day' => '$_id.day',
                            'count' => 1
                        ]
                    ]
                ]);
            })->toArray();

            foreach ($visitsByDay as $visit) {
                Analytic::create([
                    'metric' => 'visits_by_day',
                    'value' => $visit['count'],
                    'timestamp' => Carbon::parse($visit['day']),
                    'additional_info' => [
                        'description' => 'Number of visits by day'
                    ]
                ]);
            }

            foreach($this->types as $type) {
                $this->aggregateForType($type);
            }


            $this->info('Analytics metrics generated successfully.');
            Log::info('Analytics metrics generated successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred while generating analytics metrics: ' . $e->getMessage());
            Log::error('An error occurred while generating analytics metrics: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function aggregateForType($type)
    {
        $visits = Visitors::raw(function ($collection) use($type) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'record_type' => $type
                    ]
                ],
                [
                    '$group' => [
                        '_id' => [
                            '_id' => '$record_id',
                            'day' => [
                                '$dateToString' => [
                                    'format' => '%Y-%m-%d',
                                    'date' => '$updated_at'
                                ]
                            ]

                        ],
                        'count' => [
                            '$sum' => 1
                        ]
                    ]
                ],
                [
                    '$project' => [
                        $type . '_id' => '$_id._id',
                        'day' => '$_id.day',
                        'count' => 1,
                    ]
                ]
            ]);
        })->toArray();

        foreach ($visits as $visit) {
            Analytic::create([
                'metric' => 'visits_by_' . $type,
                'value' => $visit['count'],
                'timestamp' => Carbon::parse($visit['day']),
                'additional_info' => [
                    $type . '_id' => $visit[$type . '_id'],
                    'description' => 'Number of visits by ' . $type
                ]
            ]);
        }
    }
}
