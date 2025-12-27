<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('order')->get();
        return ServiceResource::collection($services);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|in:cyan,orange,teal,red,indigo,pink',
            'description' => 'required|string',
            'svg_path' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::create($request->all());

        return new ServiceResource($service);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return new ServiceResource($service);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|in:cyan,orange,teal,red,indigo,pink',
            'description' => 'required|string',
            'svg_path' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $service->update($request->all());

        return new ServiceResource($service);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}