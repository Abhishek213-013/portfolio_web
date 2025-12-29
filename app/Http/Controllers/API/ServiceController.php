<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $services = Service::orderBy('order')->get();
            return ServiceResource::collection($services);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created service.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|in:cyan,orange,teal,red,indigo,pink,purple,green',
            'description' => 'required|string',
            'svg_path' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $service = Service::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => new ServiceResource($service)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return ServiceResource|JsonResponse
     */
    public function show($id): ServiceResource|JsonResponse
    {
        try {
            $service = Service::find($id);
            
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }
            
            return new ServiceResource($service);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified service.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $service = Service::find($id);
        
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|in:cyan,orange,teal,red,indigo,pink,purple,green',
            'description' => 'required|string',
            'svg_path' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $service->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => new ServiceResource($service)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $service = Service::find($id);
        
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        try {
            $service->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the order of services.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'services' => 'required|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->services as $item) {
                Service::where('id', $item['id'])->update(['order' => $item['order']]);
            }
            
            $services = Service::orderBy('order')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Services order updated successfully',
                'data' => ServiceResource::collection($services)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update services order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active services for public display.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function active(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $services = Service::where('is_active', true)
                ->orderBy('order')
                ->get();
                
            return ServiceResource::collection($services);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update services status.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:services,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Service::whereIn('id', $request->ids)->update([
                'is_active' => $request->is_active
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Services updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update services',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}