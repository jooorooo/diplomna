<?php

namespace App\Console\Commands;

class VisitorsByIp extends AbstractVisitors
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitors:byIp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate collection for visitors by ip';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function generate()
    {
        $a = $this->getVisitorsCollection()->aggregate([
            [
                '$group' => [
                    '_id' => [
                        'ip' => '$ip',
                        'country' => '$country',
                        'date' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:00:00",
                                'date' => '$updated_at',
                            ]
                        ]
                    ],
                    'ip' => [
                        '$first' => '$ip',
                    ],
                    'count' => [
                        '$sum' => 1,
                    ]
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        '_id' => '$ip',
                    ],
                    'key' => [
                        '$first' => '$_id'
                    ],
                    'unique' => [
                        '$sum' => 1,
                    ],
                    'total' => [
                        '$sum' => '$count',
                    ],
                    'date' => [
                        '$first' => [
                            '$toDate' => '$_id.date'
                        ],
                    ],
                    'ip' => [
                        '$first' => '$ip',
                    ],
                ]
            ],
            [
                '$addFields' => [
                    '_id' => [
                        '$concat' => ['$key.date', '_', '$key.ip']
                    ]
                ]
            ],
            [
                '$project' => [
                    'key' => 0,
                ]
            ]
        ]);

        array_map(function($value) {
            $this->getCollection('visitors.ip')
                ->updateOne(['_id' => $value->_id], [
                    '$set' => $value
                ], [
                    'upsert' => true
                ]);
        }, iterator_to_array($a));

        return 0;
    }
}
