<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by stock status
        if ($request->has('in_stock')) {
            $query->where('in_stock', filter_var($request->in_stock, FILTER_VALIDATE_BOOLEAN));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->get();

        return response()->json([
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|string',
            'sizes' => 'nullable|array',
            'badge' => 'nullable|string',
            'in_stock' => 'required|boolean',
            'is_featured' => 'required|boolean',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the highest order number and add 1
        $maxOrder = Product::max('order') ?? 0;

        $productData = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'sizes' => $request->sizes,
            'badge' => $request->badge,
            'in_stock' => $request->in_stock,
            'is_featured' => $request->is_featured,
            'created_by' => $request->user()->_id,
            'order' => $maxOrder + 1,
        ];

        // Only add original_price if it's not null
        if ($request->original_price !== null) {
            $productData['original_price'] = $request->original_price;
        }

        $product = Product::create($productData);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $product)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // Find product by ID (since route model binding might not work with MongoDB)
        $productModel = Product::find($product);

        if (!$productModel) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|string',
            'sizes' => 'nullable|array',
            'badge' => 'nullable|string',
            'in_stock' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Build update data manually to handle boolean false values correctly
        $updateData = [];
        foreach ($request->all() as $key => $value) {
            // Only exclude null values, keep false/0/empty string
            if ($value !== null) {
                $updateData[$key] = $value;
            }
        }

        $productModel->update($updateData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $productModel
        ]);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Request $request, $product)
    {
        // Debug logging
        \Log::info('Delete product called with parameter:', ['product' => $product]);
        
        // Check if user is admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // Find product by ID (since route model binding might not work with MongoDB)
        $productModel = Product::find($product);
        
        \Log::info('Product search result:', ['found' => $productModel ? 'yes' : 'no']);

        if (!$productModel) {
            return response()->json([
                'message' => 'Product not found',
                'debug' => [
                    'received_id' => $product,
                    'type' => gettype($product)
                ]
            ], 404);
        }

        try {
            $productModel->delete();
            
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Request $request, $id)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->is_featured = !$product->is_featured;
        $product->save();

        return response()->json([
            'message' => 'Featured status updated',
            'product' => $product
        ]);
    }

    /**
     * Reorder products
     */
    public function reorder(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|string',
            'products.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->products as $productData) {
            Product::where('_id', $productData['id'])
                ->update(['order' => $productData['order']]);
        }

        return response()->json([
            'message' => 'Products reordered successfully'
        ]);
    }
}
