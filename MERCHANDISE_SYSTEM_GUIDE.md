# Merchandise Management System - Complete Implementation Guide

## Overview
The merchandise management system allows admins to create, edit, delete, and feature products that are displayed on the user-facing merchandise page. The system includes full CRUD operations with MongoDB storage.

## System Architecture

### Backend Components

#### 1. Product Model (`backend/app/Models/Product.php`)
MongoDB model with the following fields:
- `name` (string, required) - Product name
- `description` (string, required) - Product description
- `category` (string, required) - Product category (Apparel, Equipment, Accessories, Other)
- `price` (decimal, required) - Current selling price
- `original_price` (decimal, nullable) - Original price for showing discounts
- `image_url` (string, nullable) - Product image URL
- `sizes` (array, nullable) - Available sizes (XS, S, M, L, XL, XXL)
- `badge` (string, nullable) - Special badge text (New, Sale, Limited, etc.)
- `in_stock` (boolean, default: true) - Stock availability
- `is_featured` (boolean, default: false) - Featured product flag
- `stock_quantity` (integer, default: 0) - Available quantity
- `order` (integer, default: 0) - Display order
- `created_by` (ObjectId) - Reference to user who created the product

**Relationships:**
- `belongsTo User` (creator)

**Scopes:**
- `featured()` - Get only featured products
- `inStock()` - Get only in-stock products

#### 2. Product Controller (`backend/app/Http/Controllers/Api/ProductController.php`)
Full CRUD API with 7 methods:

**Public Endpoints (No authentication required):**
- `GET /api/products` - List all products with filters
  - Query params: `category`, `featured`, `in_stock`, `sort`
  - Returns: Array of products
  
- `GET /api/products/{id}` - Get single product details
  - Returns: Product object

**Admin-Only Endpoints (Requires authentication + admin role):**
- `POST /api/products` - Create new product
  - Body: Product fields (name, description, category, price, etc.)
  - Validation: Checks admin role via `$request->user()->isAdmin()`
  - Returns: Created product with 201 status
  
- `PUT /api/products/{id}` - Update product
  - Body: Product fields to update
  - Validation: Admin check + product exists
  - Returns: Updated product
  
- `DELETE /api/products/{id}` - Delete product
  - Validation: Admin check + product exists
  - Returns: Success message with 204 status
  
- `POST /api/products/{id}/toggle-featured` - Toggle featured status
  - Toggles `is_featured` boolean
  - Returns: Updated product
  
- `POST /api/products/reorder` - Bulk update product order
  - Body: Array of `{id, order}` objects
  - Returns: Success message

#### 3. API Routes (`backend/routes/api.php`)
```php
use App\Http\Controllers\Api\ProductController;

// Public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Protected routes (admin only)
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('products', ProductController::class)
        ->except(['index', 'show']);
    Route::post('/products/{id}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    Route::post('/products/reorder', [ProductController::class, 'reorder']);
});
```

### Frontend Components

#### 1. User Merchandise Page (`frontend/src/views/MerchandisePage.vue`)
- **Purpose:** Display products to club members and guests
- **Features:**
  - Fetches products from API on mount
  - Loading state with spinner
  - Error state with retry button
  - Product cards with image, name, category, price, sizes
  - Featured badge and sale badge display
  - Category filter buttons (All, Apparel, Equipment, Accessories)
  - Sort options (Featured, Price: Low to High, Price: High to Low, Newest)
  - Order modal for product purchase
  
- **API Integration:**
  ```javascript
  const fetchProducts = async () => {
    const response = await axios.get(`${API_URL}/products`)
    products.value = response.data.data || response.data
  }
  ```

#### 2. Admin Dashboard - Merchandise Tab (`frontend/src/views/AdminDashboard.vue`)
- **Purpose:** Full product management interface for admins
- **Features:**
  - Product grid display (3 columns on desktop)
  - Add Product button (top right)
  - Empty state with call-to-action
  - Loading state
  - Product cards showing:
    * Product image (with placeholder if none)
    * Featured badge (yellow star)
    * Out of stock badge (red)
    * Product name and category
    * Description (truncated)
    * Price and original price (strikethrough if discounted)
    * Sizes and stock quantity
  - Action buttons per product:
    * **Set/Remove Featured** - Toggle featured status
    * **Edit** - Open edit modal
    * **Delete** - Open delete confirmation

**Add/Edit Product Modal:**
- Form fields:
  * Product Name (required)
  * Description (required, textarea)
  * Category (required, dropdown: Apparel, Equipment, Accessories, Other)
  * Price (required, number)
  * Original Price (optional, for showing discounts)
  * Stock Quantity (number)
  * Image URL (url input)
  * Available Sizes (checkboxes: XS, S, M, L, XL, XXL)
  * Badge (optional text: New, Sale, Limited, etc.)
  * In Stock (checkbox)
  * Featured Product (checkbox)
  
- Validation: Required fields enforced, price > 0
- Submit buttons: Cancel or Save (Add Product / Update Product)

**Delete Confirmation Modal:**
- Shows product name
- Warning icon
- Confirmation text: "This action cannot be undone"
- Cancel or Delete buttons

### State Management

**Reactive State Variables:**
```javascript
const products = ref([])                    // All products
const loadingProducts = ref(false)          // Loading state
const showProductModal = ref(false)         // Show add/edit modal
const showDeleteModal = ref(false)          // Show delete confirmation
const editingProduct = ref(null)            // Currently editing product
const productToDelete = ref(null)           // Product to delete
const savingProduct = ref(false)            // Saving in progress
const availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']
const productForm = ref({                   // Form data
  name: '',
  description: '',
  category: '',
  price: 0,
  original_price: null,
  image_url: '',
  sizes: [],
  badge: '',
  in_stock: true,
  is_featured: false,
  stock_quantity: 0
})
```

**Key Methods:**
```javascript
fetchProducts()              // Load products from API
openProductModal(product)    // Open modal (add or edit mode)
closeProductModal()          // Close modal and reset form
saveProduct()                // Create or update product
confirmDeleteProduct(product) // Open delete confirmation
deleteProduct()              // Delete product
toggleProductFeatured(product) // Toggle featured status
```

## Usage Guide

### For Admins

#### Creating a New Product
1. Navigate to Admin Dashboard → Merchandise tab
2. Click "Add Product" button (top right)
3. Fill in all required fields:
   - Product Name (e.g., "Gators Jersey")
   - Description (detailed product info)
   - Category (select from dropdown)
   - Price (in RM)
   - Optionally add: original price, image URL, sizes, badge, stock quantity
4. Check "In Stock" if product is available
5. Check "Featured Product" to display it prominently on the user page
6. Click "Add Product" to save

#### Editing a Product
1. Find the product card in the merchandise tab
2. Click "Edit" button
3. Modify the fields as needed
4. Click "Update Product" to save changes

#### Deleting a Product
1. Find the product card in the merchandise tab
2. Click "Delete" button
3. Confirm deletion in the modal
4. Product will be removed from database and user page

#### Toggling Featured Status
1. Find the product card
2. Click "Set Featured" or "⭐ Featured" button
3. Status updates immediately
4. Featured products appear at the top of the user merchandise page

### For Users
- Navigate to Merchandise page from main navigation
- Browse products by category using filter buttons
- Sort products by featured status, price, or date added
- Click "Order Now" to initiate purchase (opens order modal)
- View product details including sizes, prices, and availability

## API Response Examples

### Get All Products
**Request:** `GET /api/products`

**Response:**
```json
{
  "data": [
    {
      "_id": "507f1f77bcf86cd799439011",
      "name": "Gators Jersey",
      "description": "Official team jersey with Gators logo",
      "category": "Apparel",
      "price": 45.00,
      "original_price": 60.00,
      "image_url": "https://example.com/jersey.jpg",
      "sizes": ["S", "M", "L", "XL"],
      "badge": "Sale",
      "in_stock": true,
      "is_featured": true,
      "stock_quantity": 15,
      "order": 1,
      "created_by": "507f1f77bcf86cd799439012",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### Create Product
**Request:** `POST /api/products`
```json
{
  "name": "Team Cap",
  "description": "Adjustable cap with embroidered logo",
  "category": "Accessories",
  "price": 25.00,
  "image_url": "https://example.com/cap.jpg",
  "sizes": ["One Size"],
  "in_stock": true,
  "is_featured": false,
  "stock_quantity": 30
}
```

**Response:**
```json
{
  "message": "Product created successfully",
  "data": {
    "_id": "507f1f77bcf86cd799439013",
    "name": "Team Cap",
    ...
  }
}
```

### Toggle Featured
**Request:** `POST /api/products/{id}/toggle-featured`

**Response:**
```json
{
  "message": "Product featured status toggled successfully",
  "data": {
    "_id": "507f1f77bcf86cd799439011",
    "is_featured": true,
    ...
  }
}
```

## Security & Authorization

### Admin-Only Operations
All mutating operations (POST, PUT, DELETE) require:
1. Valid authentication token (Sanctum)
2. User role must be 'admin'

**Authorization Check in Controller:**
```php
if (!$request->user() || !$request->user()->isAdmin()) {
    return response()->json([
        'message' => 'Unauthorized. Admin access required.'
    ], 403);
}
```

### Public Access
- Viewing products (GET /products) - No authentication required
- Anyone can browse the merchandise page
- Purchase requires login (handled by order system)

## Performance Optimizations

### Frontend
- **Debounced Search:** Admin search debounced to 300ms
- **API Caching:** Products cached for 5 minutes (if using ApiCache utility)
- **Lazy Loading:** Images loaded on demand
- **Optimized Rendering:** Vue 3 reactivity for efficient updates

### Backend
- **Response Compression:** Gzip compression via OptimizeResponse middleware
- **Cache Headers:** Cache-Control: public, max-age=120 for GET requests
- **Efficient Queries:** MongoDB indexed fields for fast retrieval
- **Pagination Ready:** Controller supports pagination (currently disabled for simplicity)

## Testing Checklist

### Backend Testing
- [ ] Create product as admin → Success
- [ ] Create product as non-admin → 403 Forbidden
- [ ] Update product as admin → Success
- [ ] Delete product as admin → Success
- [ ] Toggle featured status → Updates correctly
- [ ] Get all products (public) → Returns all products
- [ ] Filter by category → Returns filtered results
- [ ] Filter by featured → Returns only featured products

### Frontend Testing
- [ ] Admin can access merchandise tab
- [ ] Add Product modal opens and closes
- [ ] Form validation works (required fields)
- [ ] Create product → Appears in list immediately
- [ ] Edit product → Updates show in list
- [ ] Delete product → Removed from list
- [ ] Toggle featured → Badge updates instantly
- [ ] User merchandise page loads products
- [ ] Filters work correctly on user page
- [ ] Sort options work correctly
- [ ] Loading states display properly
- [ ] Error handling works (network errors)

## Database Schema

### Products Collection
```javascript
{
  _id: ObjectId,
  name: String,           // required
  description: String,    // required
  category: String,       // required
  price: Decimal,         // required
  original_price: Decimal,
  image_url: String,
  sizes: Array,
  badge: String,
  in_stock: Boolean,      // default: true
  is_featured: Boolean,   // default: false
  stock_quantity: Integer,// default: 0
  order: Integer,         // default: 0
  created_by: ObjectId,   // reference to users._id
  created_at: Date,
  updated_at: Date
}
```

### Indexes
Consider adding these indexes for better performance:
```javascript
db.products.createIndex({ category: 1 })
db.products.createIndex({ is_featured: 1 })
db.products.createIndex({ in_stock: 1 })
db.products.createIndex({ order: 1 })
db.products.createIndex({ created_by: 1 })
```

## Future Enhancements

### Potential Features
1. **Image Upload:** File upload instead of URL input
2. **Drag-and-Drop Reordering:** Visual reordering of products
3. **Bulk Operations:** Select multiple products for batch actions
4. **Product Variants:** Size-specific stock and pricing
5. **Sales Analytics:** Track popular products and revenue
6. **Inventory Management:** Low stock alerts, automatic reordering
7. **Product Reviews:** User reviews and ratings
8. **Shopping Cart:** Multi-product ordering
9. **Payment Integration:** Stripe/PayPal for online purchases
10. **Order History:** Track product orders and fulfillment

### Technical Improvements
1. **Pagination:** Implement pagination for large product lists
2. **Search:** Full-text search across product names and descriptions
3. **Image Optimization:** Automatic image resizing and compression
4. **CDN Integration:** Serve images from CDN for faster loading
5. **Real-time Updates:** WebSocket for live inventory updates
6. **Export/Import:** CSV export/import for bulk product management
7. **Product Categories Management:** Admin can create custom categories
8. **Multi-language Support:** Translations for product info

## Troubleshooting

### Common Issues

**Problem:** Products not loading in admin dashboard
- **Solution:** Check browser console for API errors. Verify authentication token is valid. Ensure backend server is running.

**Problem:** "Unauthorized" error when creating product
- **Solution:** Verify logged-in user has admin role. Check `users.role === 'admin'` in database.

**Problem:** Images not displaying
- **Solution:** Verify image URLs are valid and accessible. Check CORS settings if images are from external domains.

**Problem:** Featured toggle not working
- **Solution:** Check network tab for 403 errors. Ensure admin authorization. Verify route is correctly defined.

**Problem:** Modal not closing after save
- **Solution:** Check for JavaScript errors in console. Verify `closeProductModal()` is called after successful save.

### Debug Mode
Enable debug mode to see detailed API responses:
```javascript
// In AdminDashboard.vue, add to methods:
console.log('API Response:', response.data)
console.log('Products:', products.value)
```

## Summary

The merchandise management system is now fully functional with:
- ✅ Complete backend API (7 endpoints)
- ✅ MongoDB Product model with relationships
- ✅ Admin CRUD interface (create, read, update, delete)
- ✅ Featured product toggle
- ✅ User-facing merchandise page
- ✅ Loading and error states
- ✅ Form validation
- ✅ Authorization checks (admin-only mutations)
- ✅ Performance optimizations
- ✅ Responsive design

Admins can now fully manage club merchandise through the dashboard, and users can browse and order products through the merchandise page.
