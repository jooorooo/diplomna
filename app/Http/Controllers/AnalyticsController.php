<?php

// app/Http/Controllers/AnalyticsController.php

namespace App\Http\Controllers;

use App\Models\MongoDb\Analytic;
use Symfony\Component\Intl\Countries;

class AnalyticsController extends Controller
{
    public function index()
    {

        $analytics = Analytic::all();

        $formattedData = [
            'unique_visitors' => [
                'labels' => [],
                'values' => []
            ],
            'visits_by_country' => [],
            'visits_by_day' => [
                'labels' => [],
                'values' => []
            ],
            'visits_by_product' => [
                'labels' => [],
                'values' => []
            ],
            'visits_by_category' => [
                'labels' => [],
                'values' => []
            ],
            'visits_by_brand' => [
                'labels' => [],
                'values' => []
            ],
            'visits_by_collection' => [
                'labels' => [],
                'values' => []
            ],
        ];

        foreach ($analytics as $analytic) {
            switch ($analytic->metric) {
                case 'unique_visitors':
                    $formattedData['unique_visitors']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['unique_visitors']['values'][] = $analytic->value;
                    break;
                case 'visits_by_country':
                    $countryCode = $analytic->additional_info['country'] ?? 'Unknown';
                    try {
                        $countryName = Countries::getName($countryCode) ?? $countryCode;
                    } catch (\Symfony\Component\Intl\Exception\MissingResourceException $e) {
                        $countryName = $countryCode;
                    }
                    if (!isset($formattedData['visits_by_country'][$countryName])) {
                        $formattedData['visits_by_country'][$countryName] = [
                            'labels' => [],
                            'values' => []
                        ];
                    }

                    $formattedData['visits_by_country'][$countryName]['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_country'][$countryName]['values'][] = $analytic->value;
                    break;
                case 'visits_by_day':
                    $formattedData['visits_by_day']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_day']['values'][] = $analytic->value;
                    break;
                case 'visits_by_product':
                    $formattedData['visits_by_product']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_product']['values'][] = $analytic->value;
                    break;
                case 'visits_by_category':
                    $formattedData['visits_by_category']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_category']['values'][] = $analytic->value;
                    break;
                case 'visits_by_brand':
                    $formattedData['visits_by_brand']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_brand']['values'][] = $analytic->value;
                    break;
                case 'visits_by_collection':
                    $formattedData['visits_by_collection']['labels'][] = $analytic->timestamp->toDateString();
                    $formattedData['visits_by_collection']['values'][] = $analytic->value;
                    break;
            }
        }

        return view('analytics.index', compact('formattedData'));
    }
}
