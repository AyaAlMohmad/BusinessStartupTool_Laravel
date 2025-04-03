<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessIdea;
use App\Models\BusinessSetup;
use App\Models\FinancialPlanning;
use App\Models\LaunchPreparation;
use App\Models\Marketing;
use App\Models\MarketResearch;
use App\Models\MVPDevelopment;
use App\Models\SalesStrategy;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function downloadBusinessData()
    {
        $userId = Auth::id();

        $latestIdea = BusinessIdea::where('user_id', $userId)->latest()->first();
        $testSetup = BusinessSetup::where('user_id', $userId)->with(['licenses', 'locations', 'insurances'])->latest()->first();
        $latestPlanning = FinancialPlanning::where('user_id', $userId)->with(['startupCosts', 'fundingSources', 'revenueProjections', 'expenseProjections'])->latest()->first();
        $latestPreparation = LaunchPreparation::where('user_id', $userId)->with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])->latest()->first();
        $latestMarketing = Marketing::where('user_id', $userId)->with(['marketingChannels', 'contentStrategies', 'brandIdentity'])->latest()->first();
        $latestResearch = MarketResearch::where('user_id', $userId)->latest()->first();
        $latestDevelopment = MVPDevelopment::where('user_id', Auth::id())
        ->with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
        ->latest()->first();
        $latestSalesStrategy = SalesStrategy::with(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])->where('user_id', $userId)->latest()->first();


        $data = [
            'latest_business_idea' => $latestIdea,
            'test_setup' => $testSetup,
            'latest_financial_planning' => $latestPlanning,
            'latest_launch_preparation' => $latestPreparation,
            'latest_marketing' => $latestMarketing,
            'latest_market_research' => $latestResearch,
            'latest_mvp_development' => $latestDevelopment,
            'latest_sales_strategy' => $latestSalesStrategy,
        ];

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);


        $fileName = 'business_data_' . date('Y-m-d_H-i-s') . '.json';

      
        return new StreamedResponse(function() use ($jsonData) {
            echo $jsonData;
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}