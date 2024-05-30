<?php

namespace Database\Factories\MongoDb;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorsFactory extends Factory
{
    protected $types = [
        'product',
        'category',
        'brand',
        'collection',
    ];

    protected static $countries = [];
    protected static $userAgents = [];
    protected static $ips;

    protected $europeanCountriesIso2 = [
        'AL', 'AD', 'AM', 'AT', 'AZ', 'BY', 'BE', 'BA', 'BG', 'HR',
        'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'GE', 'DE', 'GR', 'HU',
        'IS', 'IE', 'IT', 'KZ', 'XK', 'LV', 'LT', 'LI', 'LU', 'MT',
        'MD', 'MC', 'ME', 'NL', 'MK', 'NO', 'PL', 'PT', 'RO', 'RU',
        'SM', 'RS', 'SK', 'SI', 'ES', 'SE', 'CH', 'TR', 'UA', 'GB',
        'VA',
    ];


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ip = $this->ip();
        self::$countries[$ip] = self::$countries[$ip] ?? $this->europeanCountriesIso2[array_rand($this->europeanCountriesIso2)];
        self::$userAgents[$ip] = self::$userAgents[$ip] ?? $this->faker->userAgent;

        return [
            'ip' => $ip,
            'user_agent' => self::$userAgents[$ip],
            'country' => self::$countries[$ip],
            'record_type' => $this->types[array_rand($this->types)],
            'record_id' => mt_rand(1, 9),
            'updated_at' => $this->faker->dateTimeBetween('-1 months'),
        ];
    }

    protected function ip()
    {
        if(is_null(self::$ips)) {
            self::$ips = array_map(function() {
                return $this->faker->ipv4;
            }, range(0, 1500));
        }

        return self::$ips[mt_rand(0, count(self::$ips) - 1)];
    }
}
