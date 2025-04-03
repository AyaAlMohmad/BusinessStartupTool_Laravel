<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function index()
    {
        $latestMarketing = Marketing::where('user_id', Auth::id())
            ->with(['marketingChannels', 'contentStrategies', 'brandIdentity'])
            ->latest()
            ->first();

        return response()->json($latestMarketing, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'audience_description' => 'nullable|string',
            'problem_statement' => 'nullable|string',
            'solution_overview' => 'nullable|string',
            'marketing_channels' => 'nullable|array',
            'marketing_channels.*.name' => 'nullable|string',
            'marketing_channels.*.strategy' => 'nullable|string',
            'marketing_channels.*.budget' => 'nullable|numeric',
            'marketing_channels.*.expected_roi' => 'nullable|string',
            'content_strategies' => 'nullable|array',
            'content_strategies.*.type' => 'nullable|string',
            'content_strategies.*.description' => 'nullable|string',
            'content_strategies.*.frequency' => 'nullable|string',
            'content_strategies.*.responsible_person' => 'nullable|string',
            'brand_identity' => 'nullable|array',
            'brand_identity.values' => 'nullable|array',
            'brand_identity.mission' => 'nullable|string',
            'brand_identity.vision' => 'nullable|string',
            'brand_identity.tone' => 'nullable|string',
            'brand_identity.visual_style' => 'nullable|string',
        ]);
        $validatedData['user_id'] = Auth::id();

        // إنشاء Marketing
        $marketing = Marketing::create([
            'user_id' => $validatedData['user_id'],
            'audience_description' => $validatedData['audience_description'] ?? null,
            'problem_statement' => $validatedData['problem_statement'] ?? null,
            'solution_overview' => $validatedData['solution_overview'] ?? null,
        ]);

        // إنشاء MarketingChannels
        if (isset($validatedData['marketing_channels'])) {
            foreach ($validatedData['marketing_channels'] as $channel) {
                $channel['user_id'] = $validatedData['user_id'];
                $marketing->marketingChannels()->create($channel);
            }
        }

        // إنشاء ContentStrategies
        if (isset($validatedData['content_strategies'])) {
            foreach ($validatedData['content_strategies'] as $strategy) {
                $strategy['user_id'] = $validatedData['user_id'];
                $marketing->contentStrategies()->create($strategy);
            }
        }

        // إنشاء BrandIdentity
        if (isset($validatedData['brand_identity'])) {
            $validatedData['brand_identity']['user_id'] = $validatedData['user_id'];
            $marketing->brandIdentity()->create($validatedData['brand_identity']);
        }


        return response()->json([
            'message' => 'Marketing plan created successfully',
            'data' => $marketing->load(['marketingChannels', 'contentStrategies', 'brandIdentity'])
        ], 201);
    }

    public function show($id)
    {
        return Marketing::with(['marketingChannels', 'contentStrategies', 'brandIdentity'])->findOrFail($id);
    }
    public function update(Request $request, $id)
    {
        $marketing = Marketing::findOrFail($id);
    
        $validatedData = $request->validate([
            'audience_description' => 'sometimes|nullable|string',
            'problem_statement' => 'sometimes|nullable|string',
            'solution_overview' => 'sometimes|nullable|string',
            'marketing_channels' => 'nullable|array',
            'marketing_channels.*.name' => 'sometimes|nullable|string',
            'marketing_channels.*.strategy' => 'sometimes|nullable|string',
            'marketing_channels.*.budget' => 'sometimes|nullable|numeric',
            'marketing_channels.*.expected_roi' => 'sometimes|nullable|string',
            'content_strategies' => 'nullable|array',
            'content_strategies.*.type' => 'sometimes|nullable|string',
            'content_strategies.*.description' => 'sometimes|nullable|string',
            'content_strategies.*.frequency' => 'sometimes|nullable|string',
            'content_strategies.*.responsible_person' => 'sometimes|nullable|string',
            'brand_identity' => 'nullable|array',
            'brand_identity.values' => 'nullable|array',
            'brand_identity.mission' => 'sometimes|nullable|string',
            'brand_identity.vision' => 'sometimes|nullable|string',
            'brand_identity.tone' => 'sometimes|nullable|string',
            'brand_identity.visual_style' => 'sometimes|nullable|string',
        ]);
    
        $marketing->update([
            'audience_description' => $validatedData['audience_description'] ?? $marketing->audience_description,
            'problem_statement' => $validatedData['problem_statement'] ?? $marketing->problem_statement,
            'solution_overview' => $validatedData['solution_overview'] ?? $marketing->solution_overview,
        ]);
    
        if (isset($validatedData['marketing_channels'])) {
            $marketing->marketingChannels()->delete();
            foreach ($validatedData['marketing_channels'] as $channel) {
                $channel['user_id'] = auth()->id(); // إضافة user_id
                $marketing->marketingChannels()->create($channel);
            }
        }
    
        if (isset($validatedData['content_strategies'])) {
            $marketing->contentStrategies()->delete();
            foreach ($validatedData['content_strategies'] as $strategy) {
                $strategy['user_id'] = auth()->id(); // إضافة user_id
                $marketing->contentStrategies()->create($strategy);
            }
        }
    
        if (isset($validatedData['brand_identity'])) {
            $brandIdentityData = $validatedData['brand_identity'];
            $brandIdentityData['user_id'] = auth()->id(); // إضافة user_id
            $marketing->brandIdentity()->updateOrCreate([], $brandIdentityData);
        }
    
        return response()->json([
            'message' => 'Marketing strategy updated successfully',
            'data' => $marketing->load(['marketingChannels', 'contentStrategies', 'brandIdentity'])
        ], 200);
    }

    public function destroy($id)
    {
        Marketing::destroy($id);
        return response()->json(['message' => 'Marketing strategy deleted successfully'], 204);
    }
}
