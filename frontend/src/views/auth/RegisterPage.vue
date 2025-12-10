<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Register Card -->
    <div class="card-container w-full max-w-md relative z-10 my-auto">
      <div class="register-card bg-white rounded-2xl p-6 backdrop-blur-sm shadow-lg">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-6 fade-in-up">
          <h2 class="text-2xl font-bold text-gray-800 mb-1">Join Gators DC</h2>
          <p class="text-sm text-gray-600">Step {{ currentStep }} of 3: {{ stepTitle }}</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
          <div class="flex justify-between mb-2">
            <span :class="['text-xs font-medium', currentStep >= 1 ? 'text-primary-600' : 'text-gray-400']">Personal Info</span>
            <span :class="['text-xs font-medium', currentStep >= 2 ? 'text-primary-600' : 'text-gray-400']">Account Details</span>
            <span :class="['text-xs font-medium', currentStep >= 3 ? 'text-primary-600' : 'text-gray-400']">Confirmation</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
              class="bg-gradient-to-r from-primary-600 to-primary-700 h-2 rounded-full transition-all duration-500"
              :style="{ width: `${(currentStep / 3) * 100}%` }"
            ></div>
          </div>
        </div>

        <!-- Error Message -->
        <transition name="shake">
          <div v-if="error" class="error-message bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded mb-4 text-sm">
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              {{ error }}
            </div>
          </div>
        </transition>

        <!-- Form -->
        <form @submit.prevent="handleNext" class="space-y-4">
          <!-- Step 1: Personal Information -->
          <div v-show="currentStep === 1" class="step-content">
            <div class="input-group">
              <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
              <input 
                v-model="form.name" 
                type="text" 
                :required="currentStep === 1"
                class="input-field text-sm"
                placeholder="Enter your full name"
              />
            </div>

            <div class="input-group">
              <label class="block text-xs font-medium text-gray-700 mb-1">Email Address *</label>
              <input 
                v-model="form.email" 
                type="email" 
                :required="currentStep === 1"
                class="input-field text-sm"
                placeholder="Enter your email"
              />
            </div>

            <div class="input-group">
              <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number *</label>
              <input 
                v-model="form.phone" 
                type="tel" 
                :required="currentStep === 1"
                class="input-field text-sm"
                placeholder="+60 12-345 6789"
              />
            </div>
          </div>

          <!-- Step 2: Account Details -->
          <div v-show="currentStep === 2" class="step-content">
            <div class="input-group">
              <label class="block text-xs font-medium text-gray-700 mb-1">Password *</label>
              <div class="relative">
                <input 
                  v-model="form.password" 
                  :type="showPassword ? 'text' : 'password'"
                  :required="currentStep === 2"
                  minlength="8"
                  class="input-field pr-10 text-sm"
                  placeholder="Enter your password"
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
              <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
            </div>

            <div class="input-group">
              <label class="block text-xs font-medium text-gray-700 mb-1">Confirm Password *</label>
              <div class="relative">
                <input 
                  v-model="form.password_confirmation" 
                  :type="showConfirmPassword ? 'text' : 'password'"
                  :required="currentStep === 2"
                  class="input-field pr-10 text-sm"
                  placeholder="Confirm your password"
                />
                <button 
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center z-10"
                >
                  <svg v-if="!showConfirmPassword" class="h-4 w-4 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="h-4 w-4 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Step 3: Terms & Confirmation -->
          <div v-show="currentStep === 3" class="step-content">
            <div class="bg-gray-50 rounded-lg p-4 mb-4 text-sm">
              <h3 class="font-semibold text-gray-800 mb-2">Review Your Information</h3>
              <div class="space-y-2 text-gray-600">
                <p><span class="font-medium">Name:</span> {{ form.name }}</p>
                <p><span class="font-medium">Email:</span> {{ form.email }}</p>
                <p><span class="font-medium">Phone:</span> {{ form.phone }}</p>
              </div>
            </div>

            <div class="input-group">
              <label class="flex items-start cursor-pointer">
                <input 
                  v-model="form.agreeToTerms" 
                  type="checkbox" 
                  :required="currentStep === 3"
                  class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mt-0.5 w-4 h-4"
                >
                <span class="ml-3 text-xs text-gray-700">
                  I agree to the 
                  <a href="#" class="text-primary-600 hover:text-primary-700 font-medium hover:underline">Terms and Conditions</a> 
                  and 
                  <a href="#" class="text-primary-600 hover:text-primary-700 font-medium hover:underline">Privacy Policy</a>
                </span>
              </label>
            </div>

            <div class="input-group">
              <label class="flex items-start cursor-pointer">
                <input 
                  v-model="form.subscribeNewsletter" 
                  type="checkbox" 
                  class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mt-0.5 w-4 h-4"
                >
                <span class="ml-3 text-xs text-gray-700">
                  I want to receive updates about sessions and events via email
                </span>
              </label>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <div class="flex gap-3 pt-2">
            <button 
              v-if="currentStep > 1"
              type="button"
              @click="prevStep"
              class="flex-1 bg-gray-100 text-gray-700 font-semibold py-2.5 px-6 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm"
            >
              Back
            </button>
            
            <button 
              type="submit"
              :disabled="loading"
              class="submit-button flex-1 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-sm"
            >
              <span v-if="!loading" class="flex items-center justify-center">
                <svg v-if="currentStep < 3" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ currentStep < 3 ? 'Continue' : 'Create Account' }}
              </span>
              <span v-else class="flex items-center justify-center">
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Account...
              </span>
            </button>
          </div>
        </form>

        <!-- Login Link -->
        <div class="mt-5 text-center">
          <p class="text-gray-600 text-xs">
            Already have an account? 
            <router-link to="/auth/login" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline transition-colors">
              Sign in here
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const currentStep = ref(1)

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  role: 'player',
  agreeToTerms: false,
  subscribeNewsletter: false
})

const loading = ref(false)
const error = ref(null)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const stepTitle = computed(() => {
  switch (currentStep.value) {
    case 1: return 'Personal Info'
    case 2: return 'Account Details'
    case 3: return 'Confirmation'
    default: return ''
  }
})

const validateStep = () => {
  error.value = null
  
  if (currentStep.value === 1) {
    if (!form.value.name || !form.value.email || !form.value.phone) {
      error.value = 'Please fill in all required fields'
      return false
    }
    if (!form.value.email.includes('@')) {
      error.value = 'Please enter a valid email address'
      return false
    }
  }
  
  if (currentStep.value === 2) {
    if (!form.value.password || !form.value.password_confirmation) {
      error.value = 'Please fill in all required fields'
      return false
    }
    if (form.value.password.length < 8) {
      error.value = 'Password must be at least 8 characters'
      return false
    }
    if (form.value.password !== form.value.password_confirmation) {
      error.value = 'Passwords do not match'
      return false
    }
  }
  
  if (currentStep.value === 3) {
    if (!form.value.agreeToTerms) {
      error.value = 'You must agree to the Terms and Conditions'
      return false
    }
  }
  
  return true
}

const nextStep = () => {
  if (validateStep()) {
    currentStep.value++
  }
}

const prevStep = () => {
  currentStep.value--
  error.value = null
}

const handleNext = async () => {
  if (currentStep.value < 3) {
    nextStep()
  } else {
    await handleRegister()
  }
}

const handleRegister = async () => {
  if (!validateStep()) return

  loading.value = true
  error.value = null

  try {
    await authStore.register(form.value)
    router.push('/auth/login')
  } catch (err) {
    console.error('Registration error:', err.response?.data)
    
    // Handle validation errors
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors
      const errorMessages = Object.values(errors).flat()
      error.value = errorMessages.join(', ')
    } else {
      error.value = err.response?.data?.message || 'Registration failed'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Step content transitions */
.step-content {
  animation: slideInRight 0.4s ease-out;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Card animations */
.card-container {
  animation: slideUp 0.6s ease-out;
}

.register-card {
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
  animation-delay: 0.35s;
}

.input-group:nth-child(3) {
  animation-delay: 0.4s;
}

.input-group:nth-child(4) {
  animation-delay: 0.45s;
}

.input-group:nth-child(5) {
  animation-delay: 0.5s;
}

/* Button hover effect */
.submit-button {
  position: relative;
  overflow: hidden;
  animation: fadeInUp 0.6s ease-out 0.55s both;
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
  .register-card {
    padding: 1.5rem;
  }
}
</style>