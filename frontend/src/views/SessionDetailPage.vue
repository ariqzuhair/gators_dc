<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>

    <div v-else-if="error" class="text-center py-12 text-red-600">
      {{ error }}
    </div>

    <div v-else-if="session" class="max-w-4xl mx-auto">
      <!-- Back Button -->
      <button @click="goBack" class="mb-6 flex items-center text-gray-600 hover:text-gray-900">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Sessions
      </button>

      <div class="card">
        <!-- Header -->
        <div class="mb-6">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ session.title }}</h1>
              <span class="badge text-base" :class="getTypeBadgeClass(session.type)">
                {{ session.type }}
              </span>
            </div>
            <div v-if="session.is_active" class="text-right">
              <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                Active
              </span>
            </div>
          </div>
          <p v-if="session.description" class="text-gray-600 text-lg">{{ session.description }}</p>
        </div>

        <!-- Session Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <!-- Date -->
          <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div>
              <p class="text-sm text-gray-500 font-medium">Date</p>
              <p class="text-gray-900 font-semibold">{{ formatDate(session.date) }}</p>
            </div>
          </div>

          <!-- Time -->
          <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-sm text-gray-500 font-medium">Time</p>
              <p class="text-gray-900 font-semibold">{{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}</p>
            </div>
          </div>

          <!-- Location -->
          <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <div>
              <p class="text-sm text-gray-500 font-medium">Location</p>
              <p class="text-gray-900 font-semibold">{{ session.location }}</p>
            </div>
          </div>

          <!-- Participants -->
          <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <div>
              <p class="text-sm text-gray-500 font-medium">Participants</p>
              <p class="text-gray-900 font-semibold">
                {{ session.current_participants || 0 }} / {{ session.max_participants }}
                <span v-if="isFull" class="text-red-600 text-sm ml-2">(Full)</span>
                <span v-else class="text-green-600 text-sm ml-2">({{ spotsLeft }} spots left)</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Registration Section -->
        <div v-if="isAuthenticated" class="pt-6 border-t border-gray-200">
          <!-- Membership Status Info -->
          <div v-if="showMembershipInfo" class="mb-4 p-4 border rounded-lg" :class="hasActiveMembership ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200'">
            <div class="flex items-start">
              <svg v-if="hasActiveMembership" class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <svg v-else class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p v-if="hasActiveMembership" class="text-green-800 font-medium">
                  You have an active membership - Registration is free!
                </p>
                <div v-else>
                  <p class="text-yellow-800 font-medium mb-2">
                    No active membership - Drop-in fee: RM 3.00
                  </p>
                  <p class="text-yellow-700 text-sm">
                    You'll need to upload a payment receipt to complete registration.
                  </p>
                  <p class="text-yellow-700 text-sm mt-1">
                    💡 <strong>Tip:</strong> A semester membership (RM 15) gives you unlimited access to all sessions!
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div v-if="registrationSuccess" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="text-green-800 font-medium">{{ registrationSuccessMessage }}</p>
                <p v-if="requiresPaymentVerification" class="text-green-700 text-sm mt-1">
                  Your registration is pending payment verification by an admin.
                </p>
              </div>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="registrationError" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-red-800">{{ registrationError }}</p>
            </div>
          </div>

          <!-- Registration Button -->
          <button 
            @click="initiateRegistration"
            :disabled="isFull || isRegistering || alreadyRegistered"
            class="btn btn-primary w-full md:w-auto px-8 py-3 text-lg"
            :class="{ 'opacity-50 cursor-not-allowed': isFull || isRegistering || alreadyRegistered, 'bg-green-600': alreadyRegistered }"
          >
            <span v-if="isRegistering">Registering...</span>
            <span v-else-if="alreadyRegistered">✓ Already Registered</span>
            <span v-else-if="isFull">Session Full</span>
            <span v-else>Register for Session</span>
          </button>
        </div>
        <div v-else class="pt-6 border-t border-gray-200">
          <p class="text-gray-600 mb-4">Please log in to register for this session.</p>
          <router-link to="/auth/login" class="btn btn-primary">
            Log In
          </router-link>
        </div>
      </div>

      <!-- Registered Players Section -->
      <div v-if="session.registrations && session.registrations.length > 0" class="card mt-6">
        <div class="flex items-center mb-6">
          <svg class="w-6 h-6 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <h2 class="text-2xl font-bold text-gray-900">Registered Players ({{ session.registrations.length }})</h2>
        </div>

        <div class="space-y-3">
          <div 
            v-for="registration in session.registrations" 
            :key="registration._id"
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <div class="flex items-center">
              <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                {{ getInitials(registration.player?.user?.name || 'Unknown') }}
              </div>
              <div>
                <p class="font-semibold text-gray-900">{{ registration.player?.user?.name || 'Unknown Player' }}</p>
                <p class="text-sm text-gray-500">Registered on {{ formatRegistrationDate(registration.registration_date) }}</p>
              </div>
            </div>
            <span 
              :class="{
                'bg-green-100 text-green-800': registration.status === 'registered',
                'bg-gray-100 text-gray-800': registration.status === 'cancelled',
                'bg-blue-100 text-blue-800': registration.status === 'completed'
              }"
              class="px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ registration.status }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal for Non-Members -->
    <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Drop-in Session Payment</h3>
        
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
          <p class="text-yellow-800 text-sm">
            <strong>Amount to pay: RM 3.00</strong>
          </p>
          <p class="text-yellow-700 text-sm mt-2">
            Please make payment and upload your receipt to complete registration.
          </p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Payment Receipt <span class="text-red-600">*</span>
          </label>
          <input 
            type="file" 
            @change="handleFileSelect"
            accept=".jpg,.jpeg,.png,.pdf"
            class="block w-full text-sm text-gray-500
              file:mr-4 file:py-2 file:px-4
              file:rounded-full file:border-0
              file:text-sm file:font-semibold
              file:bg-primary-50 file:text-primary-700
              hover:file:bg-primary-100"
          />
          <p class="text-xs text-gray-500 mt-1">
            Accepted formats: JPEG, PNG, PDF (max 5MB)
          </p>
        </div>

        <div v-if="paymentError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-red-800 text-sm">{{ paymentError }}</p>
        </div>

        <div class="flex gap-3">
          <button
            @click="closePaymentModal"
            :disabled="isRegistering"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50"
          >
            Cancel
          </button>
          <button
            @click="submitRegistrationWithPayment"
            :disabled="!selectedFile || isRegistering"
            class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isRegistering">Submitting...</span>
            <span v-else>Submit Registration</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useSessionStore } from '@/stores/session'
import { useAuthStore } from '@/stores/auth'
import { useRegistrationStore } from '@/stores/registration'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL

const route = useRoute()
const router = useRouter()
const sessionStore = useSessionStore()
const authStore = useAuthStore()
const registrationStore = useRegistrationStore()

const session = computed(() => sessionStore.currentSession)
const loading = computed(() => sessionStore.loading)
const error = computed(() => sessionStore.error)
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)

const isRegistering = ref(false)
const registrationError = ref(null)
const registrationSuccess = ref(false)
const registrationSuccessMessage = ref('')
const requiresPaymentVerification = ref(false)
const alreadyRegistered = ref(false)

// Membership and payment state
const hasActiveMembership = ref(false)
const showMembershipInfo = ref(false)
const showPaymentModal = ref(false)
const selectedFile = ref(null)
const paymentError = ref(null)
const currentPlayerProfile = ref(null)

const isFull = computed(() => {
  if (!session.value) return false
  return (session.value.current_participants || 0) >= session.value.max_participants
})

const spotsLeft = computed(() => {
  if (!session.value) return 0
  return session.value.max_participants - (session.value.current_participants || 0)
})

onMounted(async () => {
  await sessionStore.fetchSessionById(route.params.id)
  
  // Check if user is already registered and get membership status
  if (isAuthenticated.value && session.value) {
    await checkIfAlreadyRegistered()
    await checkMembershipStatus()
  }
})

const checkIfAlreadyRegistered = async () => {
  try {
    const token = localStorage.getItem('token')
    
    // First get the player profile for the current user
    const playersResponse = await axios.get(`${API_URL}/players`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    
    // Handle different response formats
    const players = playersResponse.data.data || playersResponse.data
    const playersArray = Array.isArray(players) ? players : []
    const userPlayer = playersArray.find(p => p.user_id === user.value._id)
    
    if (userPlayer) {
      // Check registrations for this session
      const registrationsResponse = await axios.get(`${API_URL}/registrations`, {
        params: {
          player_id: userPlayer._id,
          session_id: session.value._id
        },
        headers: { 'Authorization': `Bearer ${token}` }
      })
      
      const registrations = registrationsResponse.data.data || registrationsResponse.data
      const registrationsArray = Array.isArray(registrations) ? registrations : []
      alreadyRegistered.value = registrationsArray.some(r => r.status === 'registered')
    }
  } catch (err) {
    console.error('Error checking registration status:', err)
  }
}

const goBack = () => {
  router.push('/sessions')
}

const checkMembershipStatus = async () => {
  try {
    const token = localStorage.getItem('token')
    
    // Get player profile
    const playersResponse = await axios.get(`${API_URL}/players`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    
    const players = playersResponse.data.data || playersResponse.data
    const playersArray = Array.isArray(players) ? players : []
    const userPlayer = playersArray.find(p => p.user_id === user.value._id)
    
    if (userPlayer) {
      currentPlayerProfile.value = userPlayer
      
      // Check if player has active membership
      const currentDate = new Date()
      const currentMonth = currentDate.getMonth() + 1
      const currentYear = currentDate.getFullYear()
      const currentSemester = currentMonth <= 6 ? 'Semester 1' : 'Semester 2'
      
      const semesterMemberships = userPlayer.semester_memberships || []
      const activeMembership = semesterMemberships.find(
        m => m.semester === currentSemester && 
             parseInt(m.year) === currentYear && 
             m.status === 'active'
      )
      
      hasActiveMembership.value = !!activeMembership
      showMembershipInfo.value = true
    }
  } catch (err) {
    console.error('Error checking membership status:', err)
  }
}

const initiateRegistration = async () => {
  if (isFull.value || isRegistering.value || alreadyRegistered.value) return
  
  registrationError.value = null
  registrationSuccess.value = false
  paymentError.value = null
  
  // Get or create player profile first
  if (!currentPlayerProfile.value) {
    try {
      const token = localStorage.getItem('token')
      
      const playersResponse = await axios.get(`${API_URL}/players`, {
        headers: { 'Authorization': `Bearer ${token}` }
      })
      
      const players = playersResponse.data.data || playersResponse.data
      const playersArray = Array.isArray(players) ? players : []
      let playerProfile = playersArray.find(p => p.user_id === user.value._id)
      
      if (!playerProfile) {
        const createPlayerResponse = await axios.post(`${API_URL}/players`, {
          user_id: user.value._id,
          membership_type: 'regular',
          skill_level: 'beginner',
          emergency_contact_name: 'Not provided',
          emergency_contact_phone: 'Not provided'
        }, {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        
        playerProfile = createPlayerResponse.data.player
      }
      
      currentPlayerProfile.value = playerProfile
    } catch (err) {
      registrationError.value = 'Failed to get player profile'
      return
    }
  }
  
  // If member, register directly. If non-member, show payment modal
  if (hasActiveMembership.value) {
    await handleRegister()
  } else {
    showPaymentModal.value = true
  }
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      paymentError.value = 'File size must be less than 5MB'
      selectedFile.value = null
      return
    }
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']
    if (!allowedTypes.includes(file.type)) {
      paymentError.value = 'Only JPEG, PNG, and PDF files are allowed'
      selectedFile.value = null
      return
    }
    
    selectedFile.value = file
    paymentError.value = null
  }
}

const closePaymentModal = () => {
  showPaymentModal.value = false
  selectedFile.value = null
  paymentError.value = null
}

const submitRegistrationWithPayment = async () => {
  if (!selectedFile.value || !currentPlayerProfile.value) return
  
  isRegistering.value = true
  registrationError.value = null
  paymentError.value = null
  
  try {
    const token = localStorage.getItem('token')
    const formData = new FormData()
    formData.append('player_id', currentPlayerProfile.value._id)
    formData.append('session_id', session.value._id)
    formData.append('guest_payment_receipt', selectedFile.value)
    
    const response = await axios.post(`${API_URL}/registrations`, formData, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    })
    
    registrationSuccessMessage.value = response.data.message || 'Registration submitted successfully'
    requiresPaymentVerification.value = response.data.requires_payment_verification || false
    registrationSuccess.value = true
    alreadyRegistered.value = true
    showPaymentModal.value = false
    selectedFile.value = null
    
    // Refresh session data
    await sessionStore.fetchSessionById(route.params.id)
    
    // Hide success message after 5 seconds
    setTimeout(() => {
      registrationSuccess.value = false
    }, 5000)
    
  } catch (err) {
    paymentError.value = err.response?.data?.message || 'Failed to submit registration'
  } finally {
    isRegistering.value = false
  }
}

const handleRegister = async () => {
  if (isFull.value || isRegistering.value || alreadyRegistered.value || !currentPlayerProfile.value) return
  
  isRegistering.value = true
  registrationError.value = null
  registrationSuccess.value = false
  
  try {
    // Register for the session (member registration - no payment needed)
    await registrationStore.registerForSession(currentPlayerProfile.value._id, session.value._id)
    
    registrationSuccessMessage.value = 'Successfully registered for this session!'
    registrationSuccess.value = true
    alreadyRegistered.value = true
    
    // Refresh session data to update participant count
    await sessionStore.fetchSessionById(route.params.id)
    
    // Hide success message after 3 seconds
    setTimeout(() => {
      registrationSuccess.value = false
    }, 3000)
    
  } catch (err) {
    registrationError.value = err.response?.data?.message || err.message || 'Failed to register for session'
  } finally {
    isRegistering.value = false
  }
}

const getTypeBadgeClass = (type) => {
  const classes = {
    'drop-in': 'badge-blue',
    'training': 'badge-green',
    'tournament': 'badge-red',
    'special-event': 'badge-purple'
  }
  return classes[type] || 'badge-gray'
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const formatTime = (timeString) => {
  if (!timeString) return ''
  
  // Convert to string if it's not already
  const timeStr = String(timeString)
  
  // If it's already in HH:mm format, return it
  if (timeStr.match(/^\d{2}:\d{2}$/)) {
    return timeStr
  }
  
  // If it's a full datetime string, extract the time
  try {
    const date = new Date(timeStr)
    if (isNaN(date.getTime())) {
      return timeStr
    }
    return date.toLocaleTimeString('en-US', { 
      hour: '2-digit', 
      minute: '2-digit',
      hour12: false 
    })
  } catch (e) {
    return timeStr
  }
}

const formatRegistrationDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric',
    year: 'numeric'
  })
}

const getInitials = (name) => {
  if (!name) return '?'
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}
</script>

<style scoped>
.badge {
  @apply px-3 py-1 rounded-full text-sm font-medium;
}

.badge-blue {
  @apply bg-blue-100 text-blue-800;
}

.badge-green {
  @apply bg-green-100 text-green-800;
}

.badge-red {
  @apply bg-red-100 text-red-800;
}

.badge-purple {
  @apply bg-purple-100 text-purple-800;
}

.badge-gray {
  @apply bg-gray-100 text-gray-800;
}
</style>