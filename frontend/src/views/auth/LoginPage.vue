<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Login Card -->
    <div class="card-container w-full max-w-sm relative z-10 my-auto">
      <div class="login-card bg-white rounded-2xl p-6 backdrop-blur-sm shadow-lg">
        <!-- Back Button -->
        <router-link 
          to="/" 
          class="inline-flex items-center text-gray-600 hover:text-primary-600 transition-colors mb-4 group"
        >
          <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Home
        </router-link>

        <!-- Logo/Icon -->
        <div class="flex justify-center mb-4">
          <div class="logo-container">
            <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center transform hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-6 fade-in-up">
          <h2 class="text-2xl font-bold text-gray-800 mb-1">Welcome Back</h2>
          <p class="text-sm text-gray-600">Sign in to continue to Gators DC</p>
        </div>

        <!-- Error Alert -->
        <div v-if="error" class="mb-6 bg-red-100 border-2 border-red-400 text-red-800 px-4 py-3 rounded-lg shadow-lg">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="w-6 h-6 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <span class="font-semibold text-sm">{{ error }}</span>
            </div>
            <button @click="error = null" type="button" class="text-red-600 hover:text-red-800">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div class="input-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
            <input 
              v-model="form.email" 
              type="email" 
              required
              autocomplete="email"
              class="input-field text-sm"
              placeholder="Enter your email"
              @focus="focusedField = 'email'"
              @blur="focusedField = ''"
            />
          </div>

          <div class="input-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input 
                v-model="form.password" 
                :type="showPassword ? 'text' : 'password'"
                required
                autocomplete="current-password"
                class="input-field pr-10 text-sm"
                placeholder="Enter your password"
                @focus="focusedField = 'password'"
                @blur="focusedField = ''"
              />
              <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center z-10"
              >
                <svg v-if="!showPassword" class="h-4 w-4 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="h-4 w-4 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs">
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-3 h-3">
              <span class="ml-2 text-gray-600">Remember me</span>
            </label>
            <button type="button" @click.prevent class="text-primary-600 hover:text-primary-700 font-medium">Forgot password?</button>
          </div>

          <button 
            type="submit"
            :disabled="loading"
            class="submit-button w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-sm"
          >
            <span v-if="!loading" class="flex items-center justify-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              Sign In
            </span>
            <span v-else class="flex items-center justify-center">
              <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Signing in...
            </span>
          </button>
        </form>

        <!-- Register Link -->
        <div class="mt-5 text-center">
          <p class="text-gray-600 text-xs">
            Don't have an account? 
            <router-link to="/auth/register" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline transition-colors">
              Create one now
            </router-link>
          </p>
        </div>

        <!-- Social Login (Optional) -->
        <div class="mt-5">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs">
              <span class="px-3 bg-white text-gray-500">Or continue with</span>
            </div>
          </div>

          <div class="mt-4 flex justify-center">
            <button type="button" @click.prevent class="social-button w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref(null)
const showPassword = ref(false)
const focusedField = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = null

  try {
    await authStore.login(form.value)
    
    // Wait for store to update
    await new Promise(resolve => setTimeout(resolve, 100))
    
    // Role-based redirect
    if (authStore.isAdmin) {
      router.push('/admin')
    } else {
      router.push('/')
    }
  } catch (err) {
    // Set error message based on response
    let errorMessage = 'An error occurred during login.'
    
    if (err.response?.status === 401) {
      errorMessage = 'Invalid email or password. Please check your credentials and try again.'
    } else if (err.response?.status === 422) {
      errorMessage = err.response?.data?.message || 'Please enter valid email and password.'
    } else if (err.response?.status === 429) {
      errorMessage = 'Too many login attempts. Please try again later.'
    } else if (!err.response) {
      errorMessage = 'Unable to connect to server. Please check your internet connection.'
    } else {
      errorMessage = err.response?.data?.message || 'Login failed. Please try again.'
    }
    
    error.value = errorMessage
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Error message animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Floating background circles animation */
.floating-circle {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  animation: float 20s infinite ease-in-out;
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: -100px;
  left: -100px;
  animation-delay: 0s;
}

.circle-2 {
  width: 200px;
  height: 200px;
  top: 50%;
  right: -50px;
  animation-delay: 5s;
}

.circle-3 {
  width: 150px;
  height: 150px;
  bottom: -50px;
  left: 30%;
  animation-delay: 10s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) translateX(0) scale(1);
  }
  33% {
    transform: translateY(-30px) translateX(30px) scale(1.1);
  }
  66% {
    transform: translateY(30px) translateX(-30px) scale(0.9);
  }
}

/* Card animations */
.card-container {
  animation: slideUp 0.6s ease-out;
}

.login-card {
  animation: fadeIn 0.8s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Error message transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Logo animation */
.logo-container {
  animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3) rotate(-180deg);
  }
  50% {
    transform: scale(1.05) rotate(10deg);
  }
  70% {
    transform: scale(0.9) rotate(-5deg);
  }
  100% {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }
}

/* Title fade in animation */
.fade-in-up {
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Input field styling with focus animation */
.input-field {
  width: 100%;
  padding: 0.625rem 0.875rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
  font-size: 0.875rem;
}

.input-field:focus {
  outline: none;
  border-color: #dc2626;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
  transform: translateY(-1px);
}

.input-group {
  animation: fadeInUp 0.6s ease-out both;
}

.input-group:nth-child(1) {
  animation-delay: 0.3s;
}

.input-group:nth-child(2) {
  animation-delay: 0.4s;
}

/* Button hover effect */
.submit-button {
  position: relative;
  overflow: hidden;
  animation: fadeInUp 0.6s ease-out 0.5s both;
}

.submit-button::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.submit-button:hover::before {
  width: 300px;
  height: 300px;
}

.submit-button:active {
  transform: translateY(1px);
}

/* Social buttons */
.social-button {
  transition: all 0.2s ease;
  animation: fadeInUp 0.6s ease-out 0.6s both;
}

.social-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Error message shake animation */
.shake-enter-active {
  animation: shake 0.5s ease;
}

.shake-leave-active {
  animation: fadeOut 0.3s ease;
}

@keyframes shake {
  0%, 100% {
    transform: translateX(0);
  }
  10%, 30%, 50%, 70%, 90% {
    transform: translateX(-5px);
  }
  20%, 40%, 60%, 80% {
    transform: translateX(5px);
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}

.error-message {
  animation: shake 0.5s ease, fadeInUp 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .login-card {
    padding: 1.5rem;
  }
  
  .circle-1, .circle-2, .circle-3 {
    display: none;
  }
}
</style>
