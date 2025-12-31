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
        // Manually extract roles from FormData
        $roles = [];
        if ($request->has('roles')) {
            $roles = $request->input('roles');
            if (is_array($roles)) {
                // Ensure it's a proper indexed array
                $roles = array_values(array_filter($roles, function($role) {
                    return !empty($role) && is_string($role);
                }));
            }
        }
        
        // Manually extract social links from FormData
        $socialLinks = [];
        if ($request->has('social_links')) {
            $socialLinksInput = $request->input('social_links');
            
            if (is_array($socialLinksInput)) {
                // Process the social links array
                foreach ($socialLinksInput as $index => $link) {
                    if (isset($link['platform']) && isset($link['url'])) {
                        // Only add if both platform and url are provided
                        $socialLinks[] = [
                            'platform' => $link['platform'],
                            'url' => $link['url'],
                            'icon' => $link['icon'] ?? '',
                        ];
                    }
                }
            }
        }

        $validator = Validator::make([
            'name' => $request->name,
            'roles' => $roles,
            'background_image' => $request->background_image,
            'social_links' => $socialLinks,
            'is_active' => $request->is_active,
        ], [
            'name' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links.*.url|string|max:255',
            'social_links.*.url' => 'required_with:social_links.*.platform|string|url|max:500',
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

        $data = [
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle image upload
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('hero', 'public');
            // Store just the relative path (e.g., 'hero/filename.jpg')
            $data['background_image'] = $imagePath;
        } elseif ($request->has('background_image_url') && $request->background_image_url) {
            // Clean the URL - remove any duplicate storage prefixes
            $cleanUrl = $this->cleanImageUrl($request->background_image_url);
            $data['background_image'] = $cleanUrl;
        }

        // Encode arrays as JSON
        $data['roles'] = json_encode($roles);
        $data['social_links'] = json_encode($socialLinks);

        // Check if hero section exists
        $hero = HeroSection::first();
        
        if ($hero) {
            // Update existing
            $hero->update($data);
            $message = 'Hero section updated successfully';
        } else {
            // Create new
            $hero = HeroSection::create($data);
            $message = 'Hero section created successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => new HeroResource($hero)
        ]);
    }

    public function update(Request $request, $id)
    {
        $hero = HeroSection::findOrFail($id);

        // Manually extract roles from FormData
        $roles = [];
        if ($request->has('roles')) {
            $roles = $request->input('roles');
            if (is_array($roles)) {
                // Ensure it's a proper indexed array
                $roles = array_values(array_filter($roles, function($role) {
                    return !empty($role) && is_string($role);
                }));
            }
        }
        
        // Manually extract social links from FormData
        $socialLinks = [];
        if ($request->has('social_links')) {
            $socialLinksInput = $request->input('social_links');
            
            if (is_array($socialLinksInput)) {
                // Process the social links array
                foreach ($socialLinksInput as $index => $link) {
                    if (isset($link['platform']) && isset($link['url'])) {
                        // Only add if both platform and url are provided
                        $socialLinks[] = [
                            'platform' => $link['platform'],
                            'url' => $link['url'],
                            'icon' => $link['icon'] ?? '',
                        ];
                    }
                }
            }
        }

        $validator = Validator::make([
            'name' => $request->name,
            'roles' => $roles,
            'background_image' => $request->background_image,
            'social_links' => $socialLinks,
            'is_active' => $request->is_active,
        ], [
            'name' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links.*.url|string|max:255',
            'social_links.*.url' => 'required_with:social_links.*.platform|string|url|max:500',
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

        $data = [
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists and it's stored locally
            if ($hero->background_image && !filter_var($hero->background_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($hero->background_image);
            }
            
            $imagePath = $request->file('background_image')->store('hero', 'public');
            // Store just the relative path (e.g., 'hero/filename.jpg')
            $data['background_image'] = $imagePath;
        } elseif ($request->has('background_image_url') && $request->background_image_url) {
            // Clean the URL - remove any duplicate storage prefixes
            $cleanUrl = $this->cleanImageUrl($request->background_image_url);
            $data['background_image'] = $cleanUrl;
        }

        // Encode arrays as JSON
        $data['roles'] = json_encode($roles);
        $data['social_links'] = json_encode($socialLinks);

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

    /**
     * Clean image URL to prevent duplicate storage prefixes
     */
    private function cleanImageUrl($url)
    {
        // If it's already a full URL, return as is
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            // Remove duplicate storage/ prefixes from the path
            $parsedUrl = parse_url($url);
            $path = $parsedUrl['path'] ?? '';
            
            // Replace multiple storage/ occurrences with single storage/
            $cleanPath = preg_replace('#(storage/)+#', 'storage/', $path);
            
            // Reconstruct the URL
            return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . ($parsedUrl['port'] ?? '') . $cleanPath;
        }
        
        // If it's not a URL but has storage/ prefix, clean it
        if (str_starts_with($url, 'storage/')) {
            // Remove duplicate storage/ prefixes
            return preg_replace('#(storage/)+#', 'storage/', $url);
        }
        
        return $url;
    }
}