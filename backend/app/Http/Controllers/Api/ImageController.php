<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Upload an image
     */
    public function upload(Request $request)
    {
        // Check if user is admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        try {
            $image = $request->file('image');
            
            // Generate unique filename
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            
            // Store directly in public/uploads/products directory
            $uploadsPath = public_path('uploads/products');
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadsPath)) {
                mkdir($uploadsPath, 0755, true);
            }
            
            // Move file to public directory
            $image->move($uploadsPath, $filename);
            
            // Generate URL
            $url = env('APP_URL') . '/uploads/products/' . $filename;
            
            return response()->json([
                'message' => 'Image uploaded successfully',
                'url' => $url,
                'filename' => $filename
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image
     */
    public function delete(Request $request)
    {
        // Check if user is admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $request->validate([
            'filename' => 'required|string',
        ]);

        try {
            $filePath = public_path('uploads/products/' . $request->filename);
            
            if (file_exists($filePath)) {
                unlink($filePath);
                
                return response()->json([
                    'message' => 'Image deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Image not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
