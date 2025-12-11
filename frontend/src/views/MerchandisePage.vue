<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">Club Merchandise</h1>
      <p class="text-gray-600">Get your official Gators Dodgeball Club gear</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      <p class="mt-4 text-gray-600">Loading products...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
      <p class="text-red-800">{{ error }}</p>
      <button @click="fetchProducts" class="mt-4 btn btn-primary">Retry</button>
    </div>

    <!-- Products Display -->
    <template v-else>
      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select v-model="filterCategory" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">All Categories</option>
              <option value="jerseys">Jerseys</option>
              <option value="accessories">Accessories</option>
              <option value="equipment">Equipment</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
            <select v-model="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="name">Name</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
            </select>
          </div>
        </div>
      </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="product in filteredProducts" 
        :key="product.id"
        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group"
      >
        <!-- Product Image -->
        <div class="relative h-48 bg-gray-100 overflow-hidden flex items-center justify-center">
          <img 
            :src="product.image_url" 
            :alt="product.name"
            loading="lazy"
            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
          />
          <div v-if="product.badge" class="absolute top-3 right-3">
            <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="getBadgeClass(product.badge)">
              {{ product.badge }}
            </span>
          </div>
          <div v-if="!product.in_stock" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <span class="text-white font-bold text-lg">OUT OF STOCK</span>
          </div>
        </div>

        <!-- Product Info -->
        <div class="p-5">
          <span class="text-xs font-medium text-primary-600 uppercase">{{ product.category }}</span>
          <h3 class="text-lg font-bold text-gray-900 mt-1 mb-2">{{ product.name }}</h3>
          <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ product.description }}</p>
          
          <!-- Sizes -->
          <div v-if="product.sizes" class="mb-3">
            <p class="text-xs text-gray-500 mb-1">Available Sizes:</p>
            <div class="flex gap-1">
              <span 
                v-for="size in product.sizes" 
                :key="size"
                class="px-2 py-1 text-xs border border-gray-300 rounded"
              >
                {{ size }}
              </span>
            </div>
          </div>

          <!-- Price and Order -->
          <div class="flex items-center justify-between mt-4">
            <div>
              <span v-if="product.original_price" class="text-sm text-gray-400 line-through mr-2">
                RM {{ parseFloat(product.original_price).toFixed(2) }}
              </span>
              <span class="text-2xl font-bold text-primary-600">
                RM {{ parseFloat(product.price).toFixed(2) }}
              </span>
            </div>
          </div>

          <button
            @click="handleOrder(product)"
            :disabled="!product.in_stock"
            class="w-full mt-4 px-4 py-2 rounded-lg font-semibold transition-colors"
            :class="product.in_stock 
              ? 'bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800' 
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
          >
            <span v-if="product.in_stock">Order Now</span>
            <span v-else>Out of Stock</span>
          </button>
        </div>
      </div>
    </div>

      <!-- Empty State -->
      <div v-if="filteredProducts.length === 0" class="text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-gray-500 text-lg">No products found</p>
      </div>
    </template>

    <!-- Order Modal -->
    <div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-bold text-gray-900">Order Details</h3>
          <button @click="closeOrderModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="selectedProduct" class="space-y-4">
          <!-- Product Summary -->
          <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
            <img :src="selectedProduct.image_url" :alt="selectedProduct.name" class="w-20 h-20 object-cover rounded">
            <div>
              <h4 class="font-semibold text-gray-900">{{ selectedProduct.name }}</h4>
              <p class="text-sm text-gray-600">{{ selectedProduct.category }}</p>
              <p class="text-lg font-bold text-primary-600 mt-1">RM {{ parseFloat(selectedProduct.price).toFixed(2) }}</p>
            </div>
          </div>

          <!-- Size Selection -->
          <div v-if="selectedProduct.sizes">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Size *</label>
            <div class="grid grid-cols-4 gap-2">
              <button
                v-for="size in selectedProduct.sizes"
                :key="size"
                @click="orderForm.size = size"
                class="px-4 py-2 border rounded-lg font-medium transition-colors"
                :class="orderForm.size === size 
                  ? 'border-primary-600 bg-primary-50 text-primary-600' 
                  : 'border-gray-300 hover:border-primary-400'"
              >
                {{ size }}
              </button>
            </div>
          </div>

          <!-- Quantity -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
            <div class="flex items-center gap-3">
              <button
                @click="decreaseQuantity"
                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <input
                v-model.number="orderForm.quantity"
                type="number"
                min="1"
                class="w-20 text-center px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
              />
              <button
                @click="increaseQuantity"
                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
            <textarea
              v-model="orderForm.notes"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
              placeholder="Any special requests or customizations..."
            ></textarea>
          </div>

          <!-- Total -->
          <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-4">
              <span class="text-gray-600">Subtotal:</span>
              <span class="text-lg font-semibold">RM {{ calculateTotal().toFixed(2) }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <button
              @click="closeOrderModal"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              @click="submitOrder"
              :disabled="!isOrderValid"
              class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Confirm Order
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-md w-full p-6 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Order Submitted!</h3>
        <p class="text-gray-600 mb-6">Your order has been received. We'll contact you soon to confirm details and payment.</p>
        <button
          @click="closeSuccessModal"
          class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
        >
          Continue Shopping
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL

// Filter and sort state
const filterCategory = ref('')
const sortBy = ref('name')

// Modal state
const showOrderModal = ref(false)
const showSuccessModal = ref(false)
const selectedProduct = ref(null)

// Loading state
const loading = ref(true)
const error = ref(null)

// Order form
const orderForm = ref({
  size: '',
  quantity: 1,
  notes: ''
})

// Products from API
const products = ref([])

// Fetch products on mount
onMounted(async () => {
  await fetchProducts()
})

const fetchProducts = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get(`${API_URL}/products`)
    products.value = response.data.data || response.data.products || []
    console.log('Fetched products:', products.value.length)
  } catch (err) {
    error.value = 'Failed to load products'
    console.error('Error fetching products:', err)
  } finally {
    loading.value = false
  }
}

// Computed
const filteredProducts = computed(() => {
  let result = products.value

  // Filter by category
  if (filterCategory.value) {
    result = result.filter(p => p.category.toLowerCase() === filterCategory.value.toLowerCase())
  }

  // Sort
  if (sortBy.value === 'price-low') {
    result = [...result].sort((a, b) => a.price - b.price)
  } else if (sortBy.value === 'price-high') {
    result = [...result].sort((a, b) => b.price - a.price)
  } else {
    result = [...result].sort((a, b) => a.name.localeCompare(b.name))
  }

  return result
})

const isOrderValid = computed(() => {
  if (selectedProduct.value?.sizes && !orderForm.value.size) {
    return false
  }
  return orderForm.value.quantity > 0
})

// Methods
const getBadgeClass = (badge) => {
  const classes = {
    'Popular': 'bg-blue-100 text-blue-800',
    'Sale': 'bg-red-100 text-red-800',
    'Limited': 'bg-purple-100 text-purple-800',
    'New': 'bg-green-100 text-green-800'
  }
  return classes[badge] || 'bg-gray-100 text-gray-800'
}

const handleOrder = (product) => {
  selectedProduct.value = product
  orderForm.value = {
    size: '',
    quantity: 1,
    notes: ''
  }
  showOrderModal.value = true
}

const closeOrderModal = () => {
  showOrderModal.value = false
  selectedProduct.value = null
}

const closeSuccessModal = () => {
  showSuccessModal.value = false
}

const increaseQuantity = () => {
  orderForm.value.quantity++
}

const decreaseQuantity = () => {
  if (orderForm.value.quantity > 1) {
    orderForm.value.quantity--
  }
}

const calculateTotal = () => {
  return selectedProduct.value.price * orderForm.value.quantity
}

const submitOrder = () => {
  // TODO: Replace with actual API call
  console.log('Order submitted:', {
    product: selectedProduct.value,
    ...orderForm.value,
    total: calculateTotal()
  })

  closeOrderModal()
  showSuccessModal.value = true
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
