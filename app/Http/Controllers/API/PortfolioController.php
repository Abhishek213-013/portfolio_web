<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Display a listing of portfolio items.
     */
    public function index()
    {
        try {
            $portfolioItems = PortfolioItem::orderBy('order')->get();
            return PortfolioResource::collection($portfolioItems);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load portfolio items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created portfolio item.
     */
    public function store(Request $request)
    {
        // Convert string booleans before validation
        $requestData = $request->all();
        
        // Handle is_active conversion for validation
        if ($request->has('is_active')) {
            $isActive = $request->input('is_active');
            
            if (is_string($isActive)) {
                // Convert string representations to boolean
                $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
            }
            
            $requestData['is_active'] = (bool) $isActive;
        }

        $validator = Validator::make($requestData, [
            'title' => 'required|string|max:255',
            'category' => 'required|in:app,mobile,website,design,branding,graphic,ecommerce,other',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'required|string',
            'details_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle is_active boolean conversion
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                
                if (is_string($isActive)) {
                    $data['is_active'] = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
                } else {
                    $data['is_active'] = (bool) $isActive;
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('portfolio', 'public');
                $data['image'] = $imagePath;
            }

            // Set default order if not provided
            if (!isset($data['order'])) {
                $maxOrder = PortfolioItem::max('order');
                $data['order'] = $maxOrder ? $maxOrder + 1 : 0;
            }

            $portfolioItem = PortfolioItem::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item created successfully',
                'data' => new PortfolioResource($portfolioItem)
            ], 201);
        } catch (\Exception $e) {
            // Delete uploaded image if creation fails
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create portfolio item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified portfolio item.
     */
    public function show($id)
    {
        try {
            $portfolioItem = PortfolioItem::findOrFail($id);
            return new PortfolioResource($portfolioItem);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio item not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified portfolio item.
     */
    public function update(Request $request, $id)
    {
        try {
            $portfolioItem = PortfolioItem::findOrFail($id);

            // Convert string booleans before validation
            $requestData = $request->all();
            
            // Handle is_active conversion for validation
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                
                if (is_string($isActive)) {
                    // Convert string representations to boolean
                    $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
                }
                
                $requestData['is_active'] = (bool) $isActive;
            }

            $validator = Validator::make($requestData, [
                'title' => 'required|string|max:255',
                'category' => 'required|in:app,mobile,website,design,branding,graphic,ecommerce,other',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'description' => 'required|string',
                'details_url' => 'nullable|url',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Handle is_active boolean conversion (already done above, but keep for consistency)
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                
                if (is_string($isActive)) {
                    $data['is_active'] = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
                } else {
                    $data['is_active'] = (bool) $isActive;
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($portfolioItem->image && Storage::disk('public')->exists($portfolioItem->image)) {
                    Storage::disk('public')->delete($portfolioItem->image);
                }
                
                $imagePath = $request->file('image')->store('portfolio', 'public');
                $data['image'] = $imagePath;
            } elseif ($request->has('image_url')) {
                // If image_url is provided (existing image), extract path from URL
                $imageUrl = $request->input('image_url');
                $path = str_replace(asset('storage/'), '', $imageUrl);
                $data['image'] = $path;
            } else {
                // Keep existing image if no new image is provided
                unset($data['image']);
            }

            $portfolioItem->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item updated successfully',
                'data' => new PortfolioResource($portfolioItem)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update portfolio item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified portfolio item.
     */
    public function destroy($id)
    {
        try {
            $portfolioItem = PortfolioItem::findOrFail($id);
            
            // Delete image if exists
            if ($portfolioItem->image && Storage::disk('public')->exists($portfolioItem->image)) {
                Storage::disk('public')->delete($portfolioItem->image);
            }
            
            $portfolioItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete portfolio item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get portfolio items by category.
     */
    public function byCategory($category)
    {
        try {
            // Validate category
            $validCategories = ['app', 'mobile', 'website', 'design', 'branding', 'graphic', 'ecommerce', 'other'];
            
            if (!in_array($category, $validCategories)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category'
                ], 400);
            }
            
            $portfolioItems = PortfolioItem::where('category', $category)
                ->orderBy('order')
                ->get();
            
            return PortfolioResource::collection($portfolioItems);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load portfolio items by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active portfolio items for public display.
     */
    public function activeItems()
    {
        try {
            $portfolioItems = PortfolioItem::where('is_active', true)
                ->orderBy('order')
                ->get();
            
            return PortfolioResource::collection($portfolioItems);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load active portfolio items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update multiple portfolio items order.
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:portfolio_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $items = $request->input('items');
            
            foreach ($items as $item) {
                PortfolioItem::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Portfolio order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update portfolio order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle portfolio item active status.
     */
    public function toggleStatus($id)
    {
        try {
            $portfolioItem = PortfolioItem::findOrFail($id);
            $portfolioItem->is_active = !$portfolioItem->is_active;
            $portfolioItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio item status updated',
                'data' => new PortfolioResource($portfolioItem)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle portfolio item status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}