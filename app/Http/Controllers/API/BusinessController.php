<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessController extends Controller
{
    // الحصول على قائمة الأعمال
    public function index()
    {
        $businesses = Business::where('user_id', auth()->id())->get();
        return response()->json([
            'success' => true,
            'data' => $businesses
        ],200);
    }
    public function show($id)
    {
        $businesses = Business::where('user_id', auth()->id())->where('id', $id)->first();
        return response()->json([
            'success' => true,
            'data' => $businesses
        ],200);
    }

    // إنشاء عمل جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $business = Business::create([
            'name' => $validated['name'],
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Business created successfully',
            'data' => $business
        ], Response::HTTP_CREATED);
    }

    // عرض سجلات التعديلات لعمل معين
    public function showLogs($businessId)
    {
        $business = Business::findOrFail($businessId);
        
        $logs = AuditLog::where('table_name', 'businesses')
                       ->where('record_id', $business->id)
                       ->with('user')
                       ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'business' => $business,
                'logs' => $logs
            ]
        ],200);
    }

    // تحديث بيانات العمل
    public function update(Request $request, $businessId)
    {
        $business = Business::findOrFail($businessId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $business->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Business updated successfully',
            'data' => $business
        ], 200);
    }

    // حذف عمل
    public function destroy($businessId)
    {
        $business = Business::findOrFail($businessId);
        $business->delete();

        return response()->json([
            'success' => true,
            'message' => 'Business deleted successfully'
        ], 200);
    }
}