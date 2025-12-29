<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SiteSettingController extends Controller
{
    /**
     * Display the site settings.
     */
    public function index()
    {
        try {
            $siteSetting = SiteSetting::first();
            
            // Return empty response if no settings exist
            if (!$siteSetting) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No site settings found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => new SiteSettingResource($siteSetting),
                'message' => 'Site settings retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in SiteSettingController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve site settings',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Store new site settings.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'site_name' => 'required|string|max:255',
                'site_description' => 'required|string',
                'footer_social_links' => 'nullable|array',
                'footer_social_links.*.platform' => 'nullable|string|max:255',
                'footer_social_links.*.url' => 'nullable|url',
                'footer_social_links.*.icon' => 'nullable|string|max:255',
                'copyright_name' => 'required|string|max:255',
                'copyright_text' => 'required|string|max:255',
                'designer_name' => 'required|string|max:255',
                'designer_url' => 'required|url',
                'distributor_name' => 'required|string|max:255',
                'distributor_url' => 'required|url',
                'distributed_by_text' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Encode footer_social_links as JSON if provided
            if ($request->has('footer_social_links') && is_array($request->footer_social_links)) {
                $data['footer_social_links'] = json_encode(array_filter($request->footer_social_links, function($link) {
                    return !empty($link['platform']) || !empty($link['url']);
                }));
            } else {
                $data['footer_social_links'] = json_encode([]);
            }

            // Check if settings already exist (should only be one record)
            $siteSetting = SiteSetting::first();
            
            if ($siteSetting) {
                // Update existing
                $siteSetting->update($data);
                $message = 'Site settings updated successfully';
            } else {
                // Create new
                $siteSetting = SiteSetting::create($data);
                $message = 'Site settings created successfully';
            }

            return response()->json([
                'success' => true,
                'data' => new SiteSettingResource($siteSetting),
                'message' => $message
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in SiteSettingController@store: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save site settings',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update the specified site settings.
     */
    public function update(Request $request, $id)
    {
        try {
            $siteSetting = SiteSetting::find($id);
            
            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site settings not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'site_name' => 'required|string|max:255',
                'site_description' => 'required|string',
                'footer_social_links' => 'nullable|array',
                'footer_social_links.*.platform' => 'nullable|string|max:255',
                'footer_social_links.*.url' => 'nullable|url',
                'footer_social_links.*.icon' => 'nullable|string|max:255',
                'copyright_name' => 'required|string|max:255',
                'copyright_text' => 'required|string|max:255',
                'designer_name' => 'required|string|max:255',
                'designer_url' => 'required|url',
                'distributor_name' => 'required|string|max:255',
                'distributor_url' => 'required|url',
                'distributed_by_text' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Encode footer_social_links as JSON if provided
            if ($request->has('footer_social_links') && is_array($request->footer_social_links)) {
                $data['footer_social_links'] = json_encode(array_filter($request->footer_social_links, function($link) {
                    return !empty($link['platform']) || !empty($link['url']);
                }));
            } else {
                $data['footer_social_links'] = json_encode([]);
            }

            $siteSetting->update($data);

            return response()->json([
                'success' => true,
                'data' => new SiteSettingResource($siteSetting),
                'message' => 'Site settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SiteSettingController@update: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update site settings',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified site settings.
     */
    public function destroy($id)
    {
        try {
            $siteSetting = SiteSetting::find($id);
            
            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site settings not found'
                ], 404);
            }

            $siteSetting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Site settings deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SiteSettingController@destroy: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete site settings',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get public site settings (for frontend use)
     */
    public function publicSettings()
    {
        try {
            $siteSetting = SiteSetting::first();
            
            if (!$siteSetting) {
                // Return default values if no settings exist
                return response()->json([
                    'site_name' => 'My Portfolio',
                    'site_description' => 'A portfolio website',
                    'footer_social_links' => [],
                    'copyright_name' => 'Your Name',
                    'copyright_text' => 'All rights reserved',
                    'designer_name' => 'Designer',
                    'designer_url' => '#',
                    'distributor_name' => 'Distributor',
                    'distributor_url' => '#',
                    'distributed_by_text' => 'Distributed by'
                ]);
            }
            
            return new SiteSettingResource($siteSetting);
            
        } catch (\Exception $e) {
            Log::error('Error in SiteSettingController@publicSettings: ' . $e->getMessage());
            
            // Return default values on error
            return response()->json([
                'site_name' => 'My Portfolio',
                'site_description' => 'A portfolio website',
                'footer_social_links' => [],
                'copyright_name' => 'Your Name',
                'copyright_text' => 'All rights reserved',
                'designer_name' => 'Designer',
                'designer_url' => '#',
                'distributor_name' => 'Distributor',
                'distributor_url' => '#',
                'distributed_by_text' => 'Distributed by'
            ]);
        }
    }
}