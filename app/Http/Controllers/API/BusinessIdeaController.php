<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BusinessIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessIdeaController extends Controller
{
    public function show($id)
    {
        return response()->json(BusinessIdea::find($id), 200);
    }
    public function index()
    {
        $latestIdea = BusinessIdea::where('user_id', Auth::id())
            ->latest()
            ->first();

        return response()->json($latestIdea, 200);
    }
 // public function index()
    // {
    //     return response()->json(BusinessIdea::all(), 200);
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'skills_experience' => 'nullable|array',
            'passions_interests' => 'nullable|array',
            'values_goals' => 'nullable|array',
            'business_ideas' => 'nullable|array',
        ]);
        $data['user_id'] = Auth::id();
        $businessIdea = BusinessIdea::create($data);
        return response()->json($businessIdea, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'skills_experience' => 'nullable|array',
            'passions_interests' => 'nullable|array',
            'values_goals' => 'nullable|array',
            'business_ideas' => 'nullable|array',
        ]);
        $businessIdea = BusinessIdea::findOrFail($id);
        $businessIdea->update($data);
        return response()->json($businessIdea, 200);
    }
}