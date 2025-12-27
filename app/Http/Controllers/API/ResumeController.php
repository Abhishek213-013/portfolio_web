<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResumeResource;
use App\Models\ResumeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    public function index()
    {
        $resumeSections = ResumeSection::orderBy('type')->orderBy('order')->get();
        return ResumeResource::collection($resumeSections);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:summary,education,experience',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:255',
            'description' => 'required|string',
            'details' => 'nullable|array',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Encode details as JSON
        if ($request->has('details')) {
            $data['details'] = json_encode($request->details);
        }

        $resumeSection = ResumeSection::create($data);

        return new ResumeResource($resumeSection);
    }

    public function show($id)
    {
        $resumeSection = ResumeSection::findOrFail($id);
        return new ResumeResource($resumeSection);
    }

    public function update(Request $request, $id)
    {
        $resumeSection = ResumeSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:summary,education,experience',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:255',
            'description' => 'required|string',
            'details' => 'nullable|array',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Encode details as JSON
        if ($request->has('details')) {
            $data['details'] = json_encode($request->details);
        }

        $resumeSection->update($data);

        return new ResumeResource($resumeSection);
    }

    public function destroy($id)
    {
        $resumeSection = ResumeSection::findOrFail($id);
        $resumeSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resume section deleted successfully'
        ]);
    }

    public function byType($type)
    {
        $resumeSections = ResumeSection::where('type', $type)
            ->orderBy('order')
            ->get();
        
        return ResumeResource::collection($resumeSections);
    }
}