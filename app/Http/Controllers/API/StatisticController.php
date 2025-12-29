<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticController extends Controller
{
    public function index()
    {
        try {
            $statistics = Statistic::orderBy('order')->get();
            
            // Return as simple array for easier handling
            return response()->json([
                'success' => true,
                'data' => StatisticResource::collection($statistics)->toArray(request())
            ]);
            
            // OR simpler approach:
            // return response()->json(StatisticResource::collection($statistics));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'color' => 'nullable|string|max:50',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $statistic = Statistic::create($request->all());

        return new StatisticResource($statistic);
    }

    public function show($id)
    {
        $statistic = Statistic::findOrFail($id);
        return new StatisticResource($statistic);
    }

    public function update(Request $request, $id)
    {
        $statistic = Statistic::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'icon' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'color' => 'nullable|string|max:50',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $statistic->update($request->all());

        return new StatisticResource($statistic);
    }

    public function destroy($id)
    {
        $statistic = Statistic::findOrFail($id);
        $statistic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Statistic deleted successfully'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'statistics' => 'required|array',
            'statistics.*.id' => 'required|exists:statistics,id',
            'statistics.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->statistics as $statData) {
            Statistic::where('id', $statData['id'])->update(['order' => $statData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Statistics order updated successfully'
        ]);
    }
}