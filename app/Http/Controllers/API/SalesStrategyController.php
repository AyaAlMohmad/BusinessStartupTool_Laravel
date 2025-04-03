<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SalesStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesStrategyController extends Controller
{
    public function index()
    {
        return SalesStrategy::with(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])
            ->where('user_id', Auth::id())
            ->latest()
            ->first();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sales_channels' => 'nullable|array',
            'sales_channels.*.name' => 'nullable|string', // استخدام * للتعامل مع المصفوفات
            'sales_channels.*.description' => 'nullable|string',
            'sales_channels.*.target_revenue' => 'nullable|numeric',
            'sales_channels.*.commission_structure' => 'nullable|string',
            'pricing_tiers' => 'nullable|array',
            'pricing_tiers.*.name' => 'nullable|string',
            'pricing_tiers.*.price' => 'nullable|numeric',
            'pricing_tiers.*.features' => 'nullable|array',
            'pricing_tiers.*.target_customer' => 'nullable|string',
            'sales_processes' => 'nullable|array',
            'sales_processes.*.stage' => 'nullable|string',
            'sales_processes.*.activities' => 'nullable|string',
            'sales_processes.*.duration' => 'nullable|string',
            'sales_processes.*.responsible_person' => 'nullable|string',
            'sales_teams' => 'nullable|array',
            'sales_teams.*.role' => 'nullable|string',
            'sales_teams.*.responsibilities' => 'nullable|string',
            'sales_teams.*.required_skills' => 'nullable|string', // تأكد من أن الاسم مطابق
            'sales_teams.*.target_metrics' => 'nullable|string',
        ]);
      
        $salesStrategy = SalesStrategy::create([
            'user_id' => Auth::id(),
        ]);

        if (isset($validatedData['sales_channels'])) {
            foreach ($validatedData['sales_channels'] as $channel) {
                $channel['user_id'] = Auth::id();
                $salesStrategy->salesChannels()->create($channel);
            }
        }

        if (isset($validatedData['pricing_tiers'])) {
            foreach ($validatedData['pricing_tiers'] as $tier) {
                $tier['user_id'] = Auth::id();
                $salesStrategy->pricingTiers()->create($tier);
            }
        }

        if (isset($validatedData['sales_processes'])) {
            foreach ($validatedData['sales_processes'] as $process) {
                $process['user_id'] = Auth::id();
                $salesStrategy->salesProcesses()->create($process);
            }
        }

        if (isset($validatedData['sales_teams'])) {
            foreach ($validatedData['sales_teams'] as $team) {
                $team['user_id'] = Auth::id();
                $salesStrategy->salesTeams()->create($team);
            }
        }


        return response()->json([
            'message' => 'Sales strategy created successfully',
            'data' => $salesStrategy->load(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])
        ], 201);
    }

    public function show($id)
    {
        return SalesStrategy::with(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $salesStrategy = SalesStrategy::findOrFail($id);
    
        $validatedData = $request->validate([
            'sales_channels' => 'nullable|array',
            'sales_channels.*.name' => 'sometimes|nullable|string',
            'sales_channels.*.description' => 'sometimes|nullable|string',
            'sales_channels.*.target_revenue' => 'sometimes|nullable|numeric',
            'sales_channels.*.commission_structure' => 'sometimes|nullable|string',
            'pricing_tiers' => 'nullable|array',
            'pricing_tiers.*.name' => 'sometimes|nullable|string',
            'pricing_tiers.*.price' => 'sometimes|nullable|numeric',
            'pricing_tiers.*.features' => 'nullable|array',
            'pricing_tiers.*.target_customer' => 'sometimes|nullable|string',
            'sales_processes' => 'nullable|array',
            'sales_processes.*.stage' => 'sometimes|nullable|string',
            'sales_processes.*.activities' => 'sometimes|nullable|string',
            'sales_processes.*.duration' => 'sometimes|nullable|string',
            'sales_processes.*.responsible_person' => 'sometimes|nullable|string',
            'sales_teams' => 'nullable|array',
            'sales_teams.*.role' => 'sometimes|nullable|string',
            'sales_teams.*.responsibilities' => 'sometimes|nullable|string',
            'sales_teams.*.required_skills' => 'sometimes|nullable|string', // تأكد من وجوده هنا
            'sales_teams.*.target_metrics' => 'sometimes|nullable|string',
        ]);
    
        if (isset($validatedData['sales_channels'])) {
            $salesStrategy->salesChannels()->delete();
            foreach ($validatedData['sales_channels'] as $channel) {
                $channel['user_id'] = Auth::id();
                $salesStrategy->salesChannels()->create($channel);
            }
        }
    
        if (isset($validatedData['pricing_tiers'])) {
            $salesStrategy->pricingTiers()->delete();
            foreach ($validatedData['pricing_tiers'] as $tier) {
                $tier['user_id'] = Auth::id();
                $salesStrategy->pricingTiers()->create($tier);
            }
        }
    
        if (isset($validatedData['sales_processes'])) {
            $salesStrategy->salesProcesses()->delete();
            foreach ($validatedData['sales_processes'] as $process) {
                $process['user_id'] = Auth::id();
                $salesStrategy->salesProcesses()->create($process);
            }
        }
    
        if (isset($validatedData['sales_teams'])) {
            $salesStrategy->salesTeams()->delete();
            foreach ($validatedData['sales_teams'] as $team) {
                $team['user_id'] = Auth::id();
                $salesStrategy->salesTeams()->create([
                    'role' => $team['role'] ?? null,
                    'responsibilities' => $team['responsibilities'] ?? null,
                    'required_skills' => $team['required_skills'] ?? null, // تأكد من تعيينه هنا
                    'target_metrics' => $team['target_metrics'] ?? null,
                    'user_id' => $team['user_id'],
                ]);
            }
        }
    
        return response()->json([
            'message' => 'Sales strategy updated successfully',
            'data' => $salesStrategy->load(['salesChannels', 'pricingTiers', 'salesProcesses', 'salesTeams'])
        ], 200);
    }

    public function destroy($id)
    {
        SalesStrategy::destroy($id);
        return response()->json(['message' => 'Sales strategy deleted successfully'], 204);
    }
}
