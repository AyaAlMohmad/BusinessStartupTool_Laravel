<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MarketingChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingChannelController extends Controller
{
    public function index($marketingId)
    {
        $latestChannel = MarketingChannel::where('marketing_id', $marketingId)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($latestChannel, 200);
    }

    public function store(Request $request, $marketingId)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'strategy' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'expected_roi' => 'nullable|string',
        ]);
 
   $validatedData['user_id'] = Auth::id();
   $validatedData['marketing_id'] = $marketingId;

   $channel = MarketingChannel::create($validatedData);
        return response()->json(['message' => 'Marketing channel created successfully', 'data' => $channel], 201);
    }

    public function show($id)
    {
        return MarketingChannel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $channel = MarketingChannel::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|nullable|string',
            'strategy' => 'sometimes|nullable|string',
            'budget' => 'sometimes|nullable|numeric',
            'expected_roi' => 'sometimes|nullable|string',
        ]);

        $channel->update($validatedData);

        return response()->json(['message' => 'Marketing channel updated successfully', 'data' => $channel], 200);
    }

    public function destroy($id)
    {
        MarketingChannel::destroy($id);

        return response()->json(['message' => 'Marketing channel deleted successfully'], 204);
    }
}
