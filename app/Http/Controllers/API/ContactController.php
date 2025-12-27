<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Here you can:
        // 1. Send email
        // 2. Save to database
        // 3. Send notification
        
        // Example: Save to database (create a ContactMessage model first)
        // ContactMessage::create($request->all());

        // Example: Send email
        try {
            Mail::raw($request->message, function ($message) use ($request) {
                $message->to(env('ADMIN_EMAIL', 'admin@portfolio.com'))
                        ->subject('New Contact Form: ' . $request->subject)
                        ->replyTo($request->email, $request->name);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully!'
        ]);
    }
}