<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HeroResource;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function index()
    {
        $hero = HeroSection::first();
        return new HeroResource($hero);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('background_image');

        // Handle image upload
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('hero', 'public');
            $data['background_image'] = $imagePath;
        }

        // Encode JSON fields
        if ($request->has('roles')) {
            $data['roles'] = json_encode($request->roles);
        }

        if ($request->has('social_links')) {
            $data['social_links'] = json_encode($request->social_links);
        }

        // Check if hero section exists
        $hero = HeroSection::first();
        
        if ($hero) {
            // Update existing
            $hero->update($data);
        } else {
            // Create new
            $hero = HeroSection::create($data);
        }

        return new HeroResource($hero);
    }

    public function update(Request $request, $id)
    {
        $hero = HeroSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
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
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($hero->background_image) {
                Storage::disk('public')->delete($hero->background_image);
            }
            
            $imagePath = $request->file('background_image')->store('hero', 'public');
            $data['background_image'] = $imagePath;
        }

        // Encode JSON fields
        if ($request->has('roles')) {
            $data['roles'] = json_encode($request->roles);
        }

        if ($request->has('social_links')) {
            $data['social_links'] = json_encode($request->social_links);
        }

        $hero->update($data);

        return new HeroResource($hero);
    }

    public function destroy($id)
    {
        $hero = HeroSection::findOrFail($id);
        
        // Delete image if exists
        if ($hero->background_image) {
            Storage::disk('public')->delete($hero->background_image);
        }
        
        $hero->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero section deleted successfully'
        ]);
    }
}