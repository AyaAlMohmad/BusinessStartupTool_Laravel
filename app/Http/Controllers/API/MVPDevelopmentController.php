<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MVPDevelopment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MVPDevelopmentController extends Controller
{
    public function index()
    {
        $latestDevelopment = MVPDevelopment::where('user_id', Auth::id())
            ->with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
            ->latest()
            ->first();
    
        return response()->json($latestDevelopment, 200);
    }
    
    public function show($id)
    {
        $mvpDevelopment = MVPDevelopment::with(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
            ->findOrFail($id);
    
        return response()->json($mvpDevelopment, 200);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'features.must_have_features' => 'nullable|array',
            'features.should_have_features' => 'nullable|array',
            'features.nice_to_have_features' => 'nullable|array',
            'features.metrics' => 'nullable|array',
            'features.metrics.*.name' => 'nullable|string',
            'features.metrics.*.target_value' => 'nullable|numeric',
            'features.metrics.*.actual_value' => 'nullable|numeric',
            'assumptions' => 'nullable|array',
            'assumptions.*.description' => 'nullable|string',
            'assumptions.*.test_method' => 'nullable|string',
            'assumptions.*.success_criteria' => 'nullable|string',
            'assumptions.*.metrics' => 'nullable|array',
            'assumptions.*.metrics.*.name' => 'nullable|string',
            'assumptions.*.metrics.*.target_value' => 'nullable|numeric',
            'assumptions.*.metrics.*.actual_value' => 'nullable|numeric',
            'timelines' => 'nullable|array',
            'timelines.*.name' => 'nullable|string',
            'timelines.*.duration' => 'nullable|string',
            'timelines.*.milestones' => 'nullable|array',
            'timelines.*.metrics' => 'nullable|array',
            'timelines.*.metrics.*.name' => 'nullable|string',
            'timelines.*.metrics.*.target_value' => 'nullable|numeric',
            'timelines.*.metrics.*.actual_value' => 'nullable|numeric',
        ]);
    
        $validatedData['user_id'] = Auth::id();
    
        $mvpDevelopment = MVPDevelopment::create(['user_id' => $validatedData['user_id']]);
    
         if (isset($validatedData['features'])) {
            $featuresData = $validatedData['features'];
            $featuresData['user_id'] = $validatedData['user_id'];
            $features = $mvpDevelopment->features()->create($featuresData);
    
            if (isset($featuresData['metrics'])) {
                foreach ($featuresData['metrics'] as $metric) {
                    $metric['section_id'] = $features->id;
                    $metric['section_type'] = 'features';
                    $metric['user_id'] = $validatedData['user_id'];
                    $mvpDevelopment->metrics()->create($metric);
                }
            }
        }
    
        if (isset($validatedData['assumptions'])) {
            foreach ($validatedData['assumptions'] as $assumption) {
                $assumption['user_id'] = $validatedData['user_id'];
                $assumptionRecord = $mvpDevelopment->assumptions()->create($assumption);
    
                if (isset($assumption['metrics'])) {
                    foreach ($assumption['metrics'] as $metric) {
                        $metric['section_id'] = $assumptionRecord->id;
                        $metric['section_type'] = 'assumptions';
                        $metric['user_id'] = $validatedData['user_id'];
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        if (isset($validatedData['timelines'])) {
            foreach ($validatedData['timelines'] as $timeline) {
                $timeline['user_id'] = $validatedData['user_id'];
                $timelineRecord = $mvpDevelopment->timelines()->create($timeline);
    
                if (isset($timeline['metrics'])) {
                    foreach ($timeline['metrics'] as $metric) {
                        $metric['section_id'] = $timelineRecord->id;
                        $metric['section_type'] = 'timelines';
                        $metric['user_id'] = $validatedData['user_id'];
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        return response()->json(['message' => 'MVP Development created successfully', 'data' => $mvpDevelopment->load(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])], 201);
    }
   

    public function update(Request $request, $id)
    {
        $mvpDevelopment = MVPDevelopment::findOrFail($id);
    
        $validatedData = $request->validate([
            'features.must_have_features' => 'nullable|array',
            'features.should_have_features' => 'nullable|array',
            'features.nice_to_have_features' => 'nullable|array',
            'features.metrics' => 'nullable|array',
            'features.metrics.*.name' => 'sometimes|nullable|string',
            'features.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'features.metrics.*.actual_value' => 'sometimes|nullable|numeric',
            'assumptions' => 'nullable|array',
            'assumptions.*.description' => 'sometimes|nullable|string',
            'assumptions.*.test_method' => 'sometimes|nullable|string',
            'assumptions.*.success_criteria' => 'sometimes|nullable|string',
            'assumptions.*.metrics' => 'nullable|array',
            'assumptions.*.metrics.*.name' => 'sometimes|nullable|string',
            'assumptions.*.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'assumptions.*.metrics.*.actual_value' => 'sometimes|nullable|numeric',
            'timelines' => 'nullable|array',
            'timelines.*.name' => 'sometimes|nullable|string',
            'timelines.*.duration' => 'sometimes|nullable|string',
            'timelines.*.milestones' => 'nullable|array',
            'timelines.*.metrics' => 'nullable|array',
            'timelines.*.metrics.*.name' => 'sometimes|nullable|string',
            'timelines.*.metrics.*.target_value' => 'sometimes|nullable|numeric',
            'timelines.*.metrics.*.actual_value' => 'sometimes|nullable|numeric',
        ]);
       if (isset($validatedData['features'])) {
            $featuresData = $validatedData['features'];
            $features = $mvpDevelopment->features()->updateOrCreate([], $featuresData);
       if (isset($featuresData['metrics'])) {
                $features->metrics()->delete();
                foreach ($featuresData['metrics'] as $metric) {
                    $metric['section_id'] = $features->id;
                    $metric['section_type'] = 'features';
                    $metric['user_id'] = Auth::id();
                    $mvpDevelopment->metrics()->create($metric);
                }
            }
        }
       if (isset($validatedData['assumptions'])) {
            $mvpDevelopment->assumptions()->delete();
            foreach ($validatedData['assumptions'] as $assumption) {
                $assumption['user_id'] = Auth::id();
                $assumptionRecord = $mvpDevelopment->assumptions()->create($assumption);
      if (isset($assumption['metrics'])) {
                    foreach ($assumption['metrics'] as $metric) {
                        $metric['section_id'] = $assumptionRecord->id;
                        $metric['section_type'] = 'assumptions';
                        $metric['user_id'] = Auth::id();
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
       if (isset($validatedData['timelines'])) {
            $mvpDevelopment->timelines()->delete();
            foreach ($validatedData['timelines'] as $timeline) {
                $timeline['user_id'] = Auth::id();
                $timelineRecord = $mvpDevelopment->timelines()->create($timeline);
       if (isset($timeline['metrics'])) {
                    foreach ($timeline['metrics'] as $metric) {
                        $metric['section_id'] = $timelineRecord->id;
                        $metric['section_type'] = 'timelines';
                        $metric['user_id'] = Auth::id();
                        $mvpDevelopment->metrics()->create($metric);
                    }
                }
            }
        }
    
        return response()->json([
            'message' => 'MVP development updated successfully',
            'data' => $mvpDevelopment->load(['features.metrics', 'assumptions.metrics', 'timelines.metrics'])
        ], 200);
    }

    public function destroy($id)
    {
        MVPDevelopment::destroy($id);
        return response()->json(['message' => 'MVP development deleted successfully'], 204);
    }
}
