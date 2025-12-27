<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller
{
    public function index()
    {
        $siteSetting = SiteSetting::first();
        return new SiteSettingResource($siteSetting);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'footer_social_links' => 'nullable|array',
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
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Encode footer_social_links as JSON
        if ($request->has('footer_social_links')) {
            $data['footer_social_links'] = json_encode($request->footer_social_links);
        }

        $siteSetting = SiteSetting::updateOrCreate(
            ['id' => 1], // Assuming only one site setting
            $data
        );

        return new SiteSettingResource($siteSetting);
    }

    public function update(Request $request, $id)
    {
        $siteSetting = SiteSetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'footer_social_links' => 'nullable|array',
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
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Encode footer_social_links as JSON
        if ($request->has('footer_social_links')) {
            $data['footer_social_links'] = json_encode($request->footer_social_links);
        }

        $siteSetting->update($data);

        return new SiteSettingResource($siteSetting);
    }

    public function destroy($id)
    {
        $siteSetting = SiteSetting::findOrFail($id);
        $siteSetting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Site setting deleted successfully'
        ]);
    }
}