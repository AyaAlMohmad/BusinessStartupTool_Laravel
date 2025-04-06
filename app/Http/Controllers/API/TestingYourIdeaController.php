<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestingYourIdeaResource;
use App\Models\Business;
use App\Models\TestingYourIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TestingYourIdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $businessId = $this->getValidatedBusinessId($request);
        
        $ideas = TestingYourIdea::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->get();
            
        return TestingYourIdeaResource::collection($ideas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'desirability' => 'required|array',
            'desirability.solves_problem' => 'required|boolean',
            'desirability.problem_statement' => 'required|array',
            'desirability.existing_solutions_used' => 'required|boolean',
            'desirability.current_solutions_details' => 'nullable|array',
            'desirability.switch_reason' => 'nullable|array',
            'desirability.notes' => 'nullable|array',
            
            'feasibility' => 'required|array',
            'feasibility.required_skills' => 'required|array',
            'feasibility.qualifications_permits' => 'required|array',
            'feasibility.notes' => 'nullable|array',
            
            'viability' => 'required|array',
            'viability.payment_possible' => 'required|array',
            'viability.profitability' => 'required|array',
            'viability.finances_details' => 'required|array',
            'viability.notes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $businessId = $this->getValidatedBusinessId($request);
        $data = $this->prepareData($validator->validated());
        
        $idea = TestingYourIdea::create(array_merge($data, [
            'user_id' => Auth::id(),
            'business_id' => $businessId
        ]));

        return new TestingYourIdeaResource($idea);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $businessId = $this->getValidatedBusinessId(request());

        return TestingYourIdea::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       
        
        $businessId = $this->getValidatedBusinessId(request());

        $testingYourIdea= TestingYourIdea::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'desirability' => 'required|array',
                'desirability.solves_problem' => 'required|boolean',
                'desirability.problem_statement' => 'required|array',
                'desirability.existing_solutions_used' => 'required|boolean',
                'desirability.current_solutions_details' => 'nullable|array',
                'desirability.switch_reason' => 'nullable|array',
                'desirability.notes' => 'nullable|array',
                
                'feasibility' => 'required|array',
                'feasibility.required_skills' => 'required|array',
                'feasibility.qualifications_permits' => 'required|array',
                'feasibility.notes' => 'nullable|array',
                
                'viability' => 'required|array',
                'viability.payment_possible' => 'required|array',
                'viability.profitability' => 'required|array',
                'viability.finances_details' => 'required|array',
                'viability.notes' => 'nullable|array',
            ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $this->prepareData($validator->validated());
        $testingYourIdea->update($data);

        return new TestingYourIdeaResource($testingYourIdea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    { 
        
        $businessId = $this->getValidatedBusinessId(request());

        $testingYourIdean=TestingYourIdea::where('id', $id)
            ->where('business_id', $businessId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $testingYourIdean->delete();
        return response()->json(null, 204);
    }

    private function prepareData(array $data): array
    {
        return [
            // Desirability
            'solves_problem' => $data['desirability']['solves_problem'],
            'problem_statement' => $data['desirability']['problem_statement'],
            'existing_solutions_used' => $data['desirability']['existing_solutions_used'],
            'current_solutions_details' => $data['desirability']['current_solutions_details'] ?? null,
            'switch_reason' => $data['desirability']['switch_reason'] ?? null,
            'desirability_notes' => $data['desirability']['notes'] ?? null,
            
            // Feasibility
            'required_skills' => $data['feasibility']['required_skills'],
            'qualifications_permits' => $data['feasibility']['qualifications_permits'],
            'feasibility_notes' => $data['feasibility']['notes'] ?? null,
            
            // Viability
            'payment_possible' => $data['viability']['payment_possible'],
            'profitability' => $data['viability']['profitability'],
            'finances_details' => $data['viability']['finances_details'],
            'viability_notes' => $data['viability']['notes'] ?? null,
        ];
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