<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
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
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function downloadBusinessData(Request $request)
    {
        $userId = Auth::id();

        $businessId = $this->getValidatedBusinessId($request);

        $latestIdea = BusinessIdea::where('user_id', $userId) ->where('business_id', $businessId)->latest()->first();
        $testSetup = BusinessSetup::where('user_id', $userId) ->where('business_id', $businessId)->with(['licenses', 'locations', 'insurances'])->latest()->first();
        $latestPlanning = FinancialPlanning::where('user_id', $userId) ->where('business_id', $businessId)->with(['startupCosts', 'fundingSources', 'revenueProjections', 'expenseProjections'])->latest()->first();
        $latestPreparation = LaunchPreparation::where('user_id', $userId) ->where('business_id', $businessId)->with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])->latest()->first();
        $latestMarketing = Marketing::where('user_id', $userId) ->where('business_id', $businessId)->with(['marketingChannels', 'contentStrategies', 'brandIdentity'])->latest()->first();
        $latestResearch = MarketResearch::where('user_id', $userId) ->where('business_id', $businessId)->latest()->first();
        $latestDevelopment = MVPDevelopment::where('user_id', Auth::id()) ->where('business_id', $businessId)
        ->with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
        ->latest()->first();
        $latestSalesStrategy = SalesStrategy::with(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])->where('user_id', $userId) ->where('business_id', $businessId)->latest()->first();


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
    private function getValidatedBusinessId(Request $request)
    {
        $businessId = $request->header('business_id');
        
       
        if (!$businessId) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Missing business_id header');
        }
        
      
        $business = Business::where('id', $businessId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$business) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized access to business');
        }

        return $businessId;
    }
}