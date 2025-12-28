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
        
        // Return empty resource if no hero exists
        if (!$hero) {
            return new HeroResource(null);
        }
        
        return new HeroResource($hero);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'string|max:255',
            'social_links.*.url' => 'string|url|max:500',
            'social_links.*.icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('background_image');

        // Handle image upload
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('hero', 'public');
            $data['background_image'] = $imagePath;
        }

        // Handle background image URL if provided
        if ($request->has('background_image_url') && $request->background_image_url) {
            $data['background_image'] = $request->background_image_url;
        }

        // Handle roles - ensure it's an array
        if ($request->has('roles')) {
            $data['roles'] = json_encode($request->roles);
        } else {
            $data['roles'] = json_encode([]);
        }

        // Handle social links - ensure it's an array
        if ($request->has('social_links')) {
            $socialLinks = $request->social_links;
            // If it's a JSON string, decode it
            if (is_string($socialLinks)) {
                $socialLinks = json_decode($socialLinks, true) ?: [];
            }
            $data['social_links'] = json_encode($socialLinks);
        } else {
            $data['social_links'] = json_encode([]);
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

        return response()->json([
            'success' => true,
            'message' => 'Hero section saved successfully',
            'data' => new HeroResource($hero)
        ]);
    }

    public function update(Request $request, $id)
    {
        $hero = HeroSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('background_image');

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists and it's stored locally
            if ($hero->background_image && !filter_var($hero->background_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($hero->background_image);
            }
            
            $imagePath = $request->file('background_image')->store('hero', 'public');
            $data['background_image'] = $imagePath;
        }

        // Handle background image URL if provided
        if ($request->has('background_image_url') && $request->background_image_url) {
            $data['background_image'] = $request->background_image_url;
        }

        // Encode JSON fields
        if ($request->has('roles')) {
            $data['roles'] = json_encode($request->roles);
        }

        if ($request->has('social_links')) {
            $data['social_links'] = json_encode($request->social_links);
        }

        $hero->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Hero section updated successfully',
            'data' => new HeroResource($hero)
        ]);
    }

    public function destroy($id)
    {
        $hero = HeroSection::findOrFail($id);
        
        // Delete image if exists and it's stored locally
        if ($hero->background_image && !filter_var($hero->background_image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($hero->background_image);
        }
        
        $hero->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero section deleted successfully'
        ]);
    }
}