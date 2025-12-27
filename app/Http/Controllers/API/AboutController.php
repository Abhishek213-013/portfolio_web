<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutSection::first();
        return new AboutResource($about);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'bio' => 'required|string',
            'personal_details' => 'required|array',
            'extended_bio' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('about', 'public');
            $data['profile_image'] = $imagePath;
        }

        // Encode personal_details as JSON
        if ($request->has('personal_details')) {
            $data['personal_details'] = json_encode($request->personal_details);
        }

        $about = AboutSection::updateOrCreate(
            ['id' => 1], // Assuming only one about section
            $data
        );

        return new AboutResource($about);
    }

    public function update(Request $request, $id)
    {
        $about = AboutSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'bio' => 'required|string',
            'personal_details' => 'required|array',
            'extended_bio' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($about->profile_image) {
                Storage::disk('public')->delete($about->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('about', 'public');
            $data['profile_image'] = $imagePath;
        }

        // Encode personal_details as JSON
        if ($request->has('personal_details')) {
            $data['personal_details'] = json_encode($request->personal_details);
        }

        $about->update($data);

        return new AboutResource($about);
    }

    public function destroy($id)
    {
        $about = AboutSection::findOrFail($id);
        
        // Delete image if exists
        if ($about->profile_image) {
            Storage::disk('public')->delete($about->profile_image);
        }
        
        $about->delete();

        return response()->json([
            'success' => true,
            'message' => 'About section deleted successfully'
        ]);
    }
}