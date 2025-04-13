<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductFeature;
use App\Models\MarketingCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Business;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class MarketingNewController extends Controller
{
    
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $data = ProductFeature::with('marketingCampaigns')
            ->where('user_id', auth()->id())
            ->where('business_id', $businessId)
            ->latest()
            ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Combined data retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);
    
        $validator = Validator::make($request->all(), [
      
            'product_feature.options' => 'nullable|array',
            'product_feature.notes' => 'nullable|array',
            
            'marketing_campaign.goal' => 'nullable|array',
            'marketing_campaign.audience' => 'nullable|array',
            'marketing_campaign.format' => 'nullable|array',
            'marketing_campaign.channels' => 'nullable|array',
            'marketing_campaign.notes' => 'nullable|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            // الحصول على بيانات product_feature مع القيم الافتراضية
            $productFeatureData = $request->input('product_feature', []);
            
            // Create Product Feature
            $productFeature = ProductFeature::create(array_merge(
                [
                    'user_id' => auth()->id(),
                    'business_id' => $businessId,
                ],
                $productFeatureData
            ));
    
            // الحصول على بيانات marketing_campaign مع القيم الافتراضية
            $marketingCampaignData = $request->input('marketing_campaign', []);
            
            // Create Marketing Campaign
            $marketingCampaign = $productFeature->marketingCampaigns()->create(array_merge(
                [
                    'user_id' => auth()->id(),
                    'business_id' => $businessId,
                ],
                $marketingCampaignData
            ));
    
            DB::commit();
    
            return response()->json([
                'data' => [
                    'product_feature' => $productFeature,
                    'marketing_campaign' => $marketingCampaign
                ],
                'message' => 'Records created successfully'
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $productFeature = ProductFeature::with('marketingCampaigns')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('business_id', $businessId)
            ->firstOrFail();

        return response()->json([
            'data' => $productFeature,
            'message' => 'Record retrieved successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        $validator = Validator::make($request->all(), [
       
            'product_feature.options' => 'nullable|array',
            'product_feature.notes' => 'nullable|array',
            
            'marketing_campaign.goal' => 'nullable|array',
            'marketing_campaign.audience' => 'nullable|array',
            'marketing_campaign.format' => 'nullable|array',
            'marketing_campaign.channels' => 'nullable|array',
            'marketing_campaign.notes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update Product Feature
            $productFeature = ProductFeature::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('business_id', $businessId)
                ->firstOrFail();

            $productFeature->update($request->input('product_feature', []));

            // Update Marketing Campaign
            if ($request->has('marketing_campaign')) {
                $productFeature->marketingCampaigns()->updateOrCreate(
                    ['product_feature_id' => $id],
                    $request->input('marketing_campaign')
                );
            }

            DB::commit();

            return response()->json([
                'data' => $productFeature->load('marketingCampaigns'),
                'message' => 'Records updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $businessId = $this->getValidatedBusinessId($request);

        try {
            DB::beginTransaction();

            $productFeature = ProductFeature::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('business_id', $businessId)
                ->firstOrFail();

            // Delete related marketing campaigns
            $productFeature->marketingCampaigns()->delete();
            $productFeature->delete();

            DB::commit();

            return response()->json([
                'message' => 'Records deleted successfully'
            ], 204);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
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