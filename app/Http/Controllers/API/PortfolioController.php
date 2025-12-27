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
            'category' => 'required|in:app,product,branding',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'details_url' => 'nullable|url',
            'order' => 'integer',
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
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('portfolio', 'public');
            $data['image'] = $imagePath;
        }

        $portfolioItem = PortfolioItem::create($data);

        return new PortfolioResource($portfolioItem);
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
            'category' => 'required|in:app,product,branding',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'details_url' => 'nullable|url',
            'order' => 'integer',
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
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($portfolioItem->image) {
                Storage::disk('public')->delete($portfolioItem->image);
            }
            
            $imagePath = $request->file('image')->store('portfolio', 'public');
            $data['image'] = $imagePath;
        }

        $portfolioItem->update($data);

        return new PortfolioResource($portfolioItem);
    }

    public function destroy($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        
        // Delete image if exists
        if ($portfolioItem->image) {
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
        $portfolioItems = PortfolioItem::where('category', $category)
            ->orderBy('order')
            ->get();
        
        return PortfolioResource::collection($portfolioItems);
    }
}