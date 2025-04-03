<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LaunchPreparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaunchPreparationController extends Controller
{
    public function index()
    {
        $latestPreparation = LaunchPreparation::where('user_id', Auth::id())
            ->with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
            ->latest()
            ->first();

        return response()->json($latestPreparation, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'launch_checklists' => 'nullable|array',
            'launch_checklists.*.category' => 'nullable|string',
            'launch_checklists.*.task' => 'nullable|string',
            'launch_checklists.*.due_date' => 'nullable|date',
            'launch_checklists.*.status' => 'nullable|string',
            'launch_checklists.*.assignee' => 'nullable|string',
            'launch_checklists.*.notes' => 'nullable|string',
            'marketing_activities' => 'nullable|array',
            'marketing_activities.*.activity' => 'nullable|string',
            'marketing_activities.*.timeline' => 'nullable|string',
            'marketing_activities.*.budget' => 'nullable|numeric',
            'marketing_activities.*.status' => 'nullable|string',
            'marketing_activities.*.metrics' => 'nullable|array',
            'risk_assessments' => 'nullable|array',
            'risk_assessments.*.description' => 'nullable|string',
            'risk_assessments.*.impact' => 'nullable|string',
            'risk_assessments.*.probability' => 'nullable|string',
            'risk_assessments.*.mitigation_strategies' => 'nullable|array',
            'risk_assessments.*.contingency_plan' => 'nullable|string',
            'launch_milestones' => 'nullable|array',
            'launch_milestones.*.description' => 'nullable|string',
            'launch_milestones.*.due_date' => 'nullable|date',
            'launch_milestones.*.status' => 'nullable|string',
            'launch_milestones.*.dependencies' => 'nullable|array',
        ]);

    // إضافة user_id للمستخدم الحالي
    $validatedData['user_id'] = Auth::id();

    // إنشاء LaunchPreparation
    $launchPreparation = LaunchPreparation::create(['user_id' => $validatedData['user_id']]);

    // إنشاء LaunchChecklists
    if (isset($validatedData['launch_checklists'])) {
        foreach ($validatedData['launch_checklists'] as $checklist) {
            $checklist['user_id'] = $validatedData['user_id'];
            $launchPreparation->launchChecklists()->create($checklist);
        }
    }

    // إنشاء MarketingActivities
    if (isset($validatedData['marketing_activities'])) {
        foreach ($validatedData['marketing_activities'] as $activity) {
            $activity['user_id'] = $validatedData['user_id'];
            $launchPreparation->marketingActivities()->create($activity);
        }
    }

    // إنشاء RiskAssessments
    if (isset($validatedData['risk_assessments'])) {
        foreach ($validatedData['risk_assessments'] as $risk) {
            $risk['user_id'] = $validatedData['user_id'];
            $launchPreparation->riskAssessments()->create($risk);
        }
    }

    // إنشاء LaunchMilestones
    if (isset($validatedData['launch_milestones'])) {
        foreach ($validatedData['launch_milestones'] as $milestone) {
            $milestone['user_id'] = $validatedData['user_id'];
            $launchPreparation->launchMilestones()->create($milestone);
        }
    }

        return response()->json([
            'message' => 'Launch preparation created successfully',
            'data' => $launchPreparation->load(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
        ], 201);
    }

    public function show($id)
    {
        $launchPreparation = LaunchPreparation::with(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])->findOrFail($id);
        return response()->json($launchPreparation, 200);
    }

    public function update(Request $request, $id)
    {
        $launchPreparation = LaunchPreparation::findOrFail($id);
    
        $validatedData = $request->validate([
            'launch_checklists' => 'nullable|array',
            'launch_checklists.*.category' => 'sometimes|nullable|string',
            'launch_checklists.*.task' => 'sometimes|nullable|string',
            'launch_checklists.*.due_date' => 'sometimes|nullable|date',
            'launch_checklists.*.status' => 'sometimes|nullable|string',
            'launch_checklists.*.assignee' => 'sometimes|nullable|string',
            'launch_checklists.*.notes' => 'nullable|string',
            'marketing_activities' => 'nullable|array',
            'marketing_activities.*.activity' => 'sometimes|nullable|string',
            'marketing_activities.*.timeline' => 'sometimes|nullable|string',
            'marketing_activities.*.budget' => 'sometimes|nullable|numeric',
            'marketing_activities.*.status' => 'sometimes|nullable|string',
            'marketing_activities.*.metrics' => 'nullable|array',
            'risk_assessments' => 'nullable|array',
            'risk_assessments.*.description' => 'sometimes|nullable|string',
            'risk_assessments.*.impact' => 'sometimes|nullable|string',
            'risk_assessments.*.probability' => 'sometimes|nullable|string',
            'risk_assessments.*.mitigation_strategies' => 'nullable|array',
            'risk_assessments.*.contingency_plan' => 'nullable|string',
            'launch_milestones' => 'nullable|array',
            'launch_milestones.*.description' => 'sometimes|nullable|string',
            'launch_milestones.*.due_date' => 'sometimes|nullable|date',
            'launch_milestones.*.status' => 'sometimes|nullable|string',
            'launch_milestones.*.dependencies' => 'nullable|array',
        ]);
    
        // تحديث أو إنشاء LaunchChecklists
        if (isset($validatedData['launch_checklists'])) {
            $launchPreparation->launchChecklists()->delete();
            foreach ($validatedData['launch_checklists'] as $checklist) {
                $checklist['user_id'] = Auth::id(); // تعيين user_id
                $launchPreparation->launchChecklists()->create($checklist);
            }
        }
    
        // تحديث أو إنشاء MarketingActivities
        if (isset($validatedData['marketing_activities'])) {
            $launchPreparation->marketingActivities()->delete();
            foreach ($validatedData['marketing_activities'] as $activity) {
                $activity['user_id'] = Auth::id(); // تعيين user_id
                $launchPreparation->marketingActivities()->create($activity);
            }
        }
    
        // تحديث أو إنشاء RiskAssessments
        if (isset($validatedData['risk_assessments'])) {
            $launchPreparation->riskAssessments()->delete();
            foreach ($validatedData['risk_assessments'] as $risk) {
                $risk['user_id'] = Auth::id(); // تعيين user_id
                $launchPreparation->riskAssessments()->create($risk);
            }
        }
    
        // تحديث أو إنشاء LaunchMilestones
        if (isset($validatedData['launch_milestones'])) {
            $launchPreparation->launchMilestones()->delete();
            foreach ($validatedData['launch_milestones'] as $milestone) {
                $milestone['user_id'] = Auth::id(); // تعيين user_id
                $launchPreparation->launchMilestones()->create($milestone);
            }
        }
    
        return response()->json([
            'message' => 'Launch preparation updated successfully',
            'data' => $launchPreparation->load(['launchChecklists', 'marketingActivities', 'riskAssessments', 'launchMilestones'])
        ], 200);
    }

    public function destroy($id)
    {
        LaunchPreparation::destroy($id);
        return response()->json(['message' => 'Launch preparation deleted successfully'], 204);
    }
}
