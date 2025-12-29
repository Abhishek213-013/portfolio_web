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
    public function index()
    {
        $portfolioItems = PortfolioItem::orderBy('order')->get();
        return PortfolioResource::collection($portfolioItems);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|in:app,mobile,website,design,branding,graphic,ecommerce,other',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Increased to 5MB
            'description' => 'required|string',
            'details_url' => 'nullable|url',
            'order' => 'integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Convert string '1'/'0' to boolean if needed
        if (is_string($request->input('is_active'))) {
            $data['is_active'] = $request->input('is_active') === '1' || $request->input('is_active') === 'true';
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('portfolio', 'public');
            $data['image'] = $imagePath;
        }

        $portfolioItem = PortfolioItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Portfolio item created successfully',
            'data' => new PortfolioResource($portfolioItem)
        ], 201);
    }

    public function show($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        return new PortfolioResource($portfolioItem);
    }

    public function update(Request $request, $id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|in:app,mobile,website,design,branding,graphic,ecommerce,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'required|string',
            'details_url' => 'nullable|url',
            'order' => 'integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Convert string '1'/'0' to boolean if needed
        if (is_string($request->input('is_active'))) {
            $data['is_active'] = $request->input('is_active') === '1' || $request->input('is_active') === 'true';
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($portfolioItem->image && Storage::disk('public')->exists($portfolioItem->image)) {
                Storage::disk('public')->delete($portfolioItem->image);
            }
            
            $imagePath = $request->file('image')->store('portfolio', 'public');
            $data['image'] = $imagePath;
        }

        $portfolioItem->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Portfolio item updated successfully',
            'data' => new PortfolioResource($portfolioItem)
        ]);
    }

    public function destroy($id)
    {
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
    }

    public function byCategory($category)
    {
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
    }
}