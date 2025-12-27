<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('order')->get();
        return SkillResource::collection($skills);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'column' => 'required|in:left,right',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $skill = Skill::create($request->all());

        return new SkillResource($skill);
    }

    public function show($id)
    {
        $skill = Skill::findOrFail($id);
        return new SkillResource($skill);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'column' => 'required|in:left,right',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $skill->update($request->all());

        return new SkillResource($skill);
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.id' => 'required|exists:skills,id',
            'skills.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->skills as $skillData) {
            Skill::where('id', $skillData['id'])->update(['order' => $skillData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skills order updated successfully'
        ]);
    }
}