<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactInfoResource;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactInfoController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::orderBy('order')->get();
        return ContactInfoResource::collection($contactInfo);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:address,phone,email',
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contactInfo = ContactInfo::create($request->all());

        return new ContactInfoResource($contactInfo);
    }

    public function show($id)
    {
        $contactInfo = ContactInfo::findOrFail($id);
        return new ContactInfoResource($contactInfo);
    }

    public function update(Request $request, $id)
    {
        $contactInfo = ContactInfo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:address,phone,email',
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contactInfo->update($request->all());

        return new ContactInfoResource($contactInfo);
    }

    public function destroy($id)
    {
        $contactInfo = ContactInfo::findOrFail($id);
        $contactInfo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact info deleted successfully'
        ]);
    }
}