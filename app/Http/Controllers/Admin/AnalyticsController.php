<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;


class AnalyticsController extends Controller
{
    protected $client;
    protected $property;

    public function __construct()
    {
        $keyFile = storage_path('app/google/service-account.json');

        if (!file_exists($keyFile)) {
            throw new \RuntimeException("GA service account JSON not found at: {$keyFile}");
        }

        $this->client = new BetaAnalyticsDataClient([
            'credentials' => $keyFile,
        ]);

        $this->property = 'properties/' . config('services.ga.property_id');
    }

    public function index()
    {
        // Live visitors (realtime)
        $reqRealtime = new RunRealtimeReportRequest([
            'property' => $this->property,
            'metrics' => [
                ['name' => 'activeUsers']
            ],
        ]);
        $respRealtime = $this->client->runRealtimeReport($reqRealtime);
        $liveVisitors = $respRealtime->getRows()[0]->getMetricValues()[0]->getValue() ?? 0;

        // Total pageviews last 7 days
        $reqPV = new RunReportRequest([
            'property' => $this->property,
            'metrics' => [
                ['name' => 'screenPageViews']
            ],
            'dateRanges' => [
                ['startDate' => '7daysAgo', 'endDate' => 'today']
            ],
        ]);
        $respPV = $this->client->runReport($reqPV);
        $totalPageviews = $respPV->getRows()[0]->getMetricValues()[0]->getValue() ?? 0;

        // Avg session duration last 7 days
        $reqASD = new RunReportRequest([
            'property' => $this->property,
            'metrics' => [
                ['name' => 'averageSessionDuration']
            ],
            'dateRanges' => [
                ['startDate' => '7daysAgo', 'endDate' => 'today']
            ],
        ]);
        $respASD = $this->client->runReport($reqASD);
        $avgSessionDuration = $respASD->getRows()[0]->getMetricValues()[0]->getValue() ?? 0;

        // Conversion rate (using sessions & conversions)
        $reqConv = new RunReportRequest([
            'property' => $this->property,
            'metrics' => [
                ['name' => 'sessions'],
                ['name' => 'conversions']
            ],
            'dateRanges' => [
                ['startDate' => '7daysAgo', 'endDate' => 'today']
            ],
        ]);
        $respConv = $this->client->runReport($reqConv);
        $rowsConv = $respConv->getRows();
        $sessions = $rowsConv[0]->getMetricValues()[0]->getValue() ?? 0;
        $conversions = $rowsConv[0]->getMetricValues()[1]->getValue() ?? 0;
        $conversionRate = $sessions > 0 ? round(($conversions / $sessions) * 100, 2) : 0;

        return view('admin.analytics', compact(
            'liveVisitors',
            'totalPageviews',
            'avgSessionDuration',
            'conversionRate'
        ));
    }
}
