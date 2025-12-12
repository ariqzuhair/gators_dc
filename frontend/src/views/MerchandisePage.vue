<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50/20">
    <div class="container mx-auto px-4 lg:px-6 py-6 md:py-10">
      <!-- Modern Header -->
      <div class="mb-6 md:mb-8">
        <div class="flex items-center mb-2">
          <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center mr-3 shadow-lg">
            <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-1">Club Merchandise</h1>
            <p class="text-gray-600 text-sm md:text-base">Get your official Gators Dodgeball Club gear</p>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-primary-200 border-t-primary-600"></div>
        <p class="mt-4 text-gray-600 text-lg">Loading products...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-gradient-to-br from-red-50 to-red-100/50 border-2 border-red-300 rounded-2xl p-8 text-center shadow-lg">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <p class="text-red-900 font-semibold text-lg mb-4">{{ error }}</p>
        <button @click="fetchProducts" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
          Retry
        </button>
      </div>

      <!-- Products Display -->
      <template v-else>
        <!-- Modern Filters Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
          <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900">Filter & Sort</h2>
          </div>
          <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
              <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
              <select v-model="filterCategory" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <option value="">All Categories</option>
                <option value="jerseys">Jerseys</option>
                <option value="accessories">Accessories</option>
                <option value="equipment">Equipment</option>
              </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
              <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
              <select v-model="sortBy" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <option value="name">Name</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
              </select>
            </div>
          </div>
        </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <div 
          v-for="product in filteredProducts" 
          :key="product.id"
          class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden"
        >
          <!-- Product Image -->
          <div class="relative h-48 md:h-56 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden flex items-center justify-center">
            <img 
              :src="product.image_url" 
              :alt="product.name"
              loading="lazy"
              class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500"
            />
            <div v-if="product.badge" class="absolute top-3 right-3">
              <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-lg" :class="getBadgeClass(product.badge)">
                {{ product.badge }}
              </span>
            </div>
            <div v-if="!product.in_stock" class="absolute inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center">
              <span class="text-white font-bold text-lg px-6 py-3 bg-red-600 rounded-xl shadow-xl">OUT OF STOCK</span>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-4 md:p-5">
            <span class="inline-block px-2.5 py-0.5 bg-primary-50 text-primary-700 text-xs font-bold uppercase rounded-lg mb-2">{{ product.category }}</span>
            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">{{ product.name }}</h3>
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ product.description }}</p>
            
            <!-- Sizes -->
            <div v-if="product.sizes" class="mb-4">
              <p class="text-xs font-medium text-gray-500 mb-2">Available Sizes:</p>
              <div class="flex gap-2 flex-wrap">
                <span 
                  v-for="size in product.sizes" 
                  :key="size"
                  class="px-3 py-1.5 text-xs font-semibold border-2 border-gray-200 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors"
                >
                  {{ size }}
                </span>
              </div>
            </div>

            <!-- Price and Order -->
            <div class="mb-3">
              <div class="flex items-baseline gap-2">
                <span v-if="product.original_price" class="text-xs text-gray-400 line-through">
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
              class="w-full px-4 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg"
              :class="product.in_stock 
                ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white hover:from-primary-700 hover:to-primary-800 hover:shadow-xl transform hover:scale-105' 
                : 'bg-gray-200 text-gray-500 cursor-not-allowed'"
            >
              <span v-if="product.in_stock" class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Order Now
              </span>
              <span v-else>Out of Stock</span>
            </button>
          </div>
        </div>
      </div>

        <!-- Empty State -->
        <div v-if="filteredProducts.length === 0" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <p class="text-gray-600 text-lg">No products found</p>
        </div>
      </template>

      <!-- Order Modal -->
      <div v-if="showOrderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
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
