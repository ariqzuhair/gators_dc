<template>
  <div>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-900 via-primary-900 to-primary-800 text-white overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:50px_50px]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent"></div>
      
      <div class="relative container mx-auto px-4 lg:px-6 py-16 md:py-24">
        <div class="max-w-4xl">
          <div class="inline-block px-3 py-1.5 bg-primary-500/20 backdrop-blur-sm border border-primary-400/30 rounded-full text-xs md:text-sm font-medium mb-4 md:mb-6">
            ✨ Welcome to the Community
          </div>
          <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 md:mb-6 leading-tight">
            Welcome to
            <span class="bg-gradient-to-r from-primary-400 to-red-400 bg-clip-text text-transparent">
              Gators Dodgeball Club
            </span>
          </h1>
          <p class="text-base md:text-lg lg:text-xl mb-6 md:mb-8 text-gray-300 leading-relaxed max-w-2xl">
            Join the most exciting dodgeball community! Register for drop-in sessions, 
            training programs, and competitive tournaments.
          </p>
          <div class="flex flex-wrap gap-4">
            <router-link to="/sessions" class="px-8 py-4 bg-white text-primary-600 font-semibold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center space-x-2">
              <span>View Sessions</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </router-link>
            <router-link v-if="!isAuthenticated" to="/auth/register" class="px-8 py-4 bg-gradient-to-r from-primary-600 to-red-600 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200">
              Join Now
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 lg:px-6 py-20">
      <!-- Features Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200">
          <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3 text-gray-800 text-center">Flexible Sessions</h3>
          <p class="text-gray-600 text-center leading-relaxed">Drop-in sessions, training programs, and tournaments available</p>
        </div>

        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200">
          <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3 text-gray-800 text-center">Great Community</h3>
          <p class="text-gray-600 text-center leading-relaxed">Join a welcoming community of dodgeball enthusiasts</p>
        </div>

        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200">
          <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3 text-gray-800 text-center">All Skill Levels</h3>
          <p class="text-gray-600 text-center leading-relaxed">From beginners to advanced players, everyone is welcome</p>
        </div>
      </div>

      <!-- Upcoming Sessions -->
      <div>
        <div class="flex justify-between items-center mb-10">
          <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Upcoming Sessions</h2>
            <p class="text-gray-600">Join us for our next events</p>
          </div>
          <router-link to="/sessions" class="group flex items-center space-x-2 px-6 py-3 bg-primary-50 text-primary-600 hover:bg-primary-100 font-semibold rounded-xl transition-all duration-200">
            <span>View All</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </router-link>
        </div>

        <div v-if="loading" class="text-center py-20">
          <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-primary-200 border-t-primary-600"></div>
          <p class="mt-4 text-gray-600">Loading sessions...</p>
        </div>

        <div v-else-if="error" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <p class="text-red-600 font-medium">{{ error }}</p>
        </div>

        <div v-else-if="upcomingSessions.length === 0" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <p class="text-gray-600">No upcoming sessions at the moment.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <SessionCard 
            v-for="session in upcomingSessions" 
            :key="session._id"
            :session="session"
            :show-register-button="isAuthenticated"
            :is-registered="isSessionRegistered(session._id)"
            @register="handleRegister"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useSessionStore } from '@/stores/session'
import { useAuthStore } from '@/stores/auth'
import SessionCard from '@/components/SessionCard.vue'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL

const router = useRouter()
const sessionStore = useSessionStore()
const authStore = useAuthStore()

const upcomingSessions = ref([])
const loading = ref(false)
const error = ref(null)
const userRegistrations = ref([])

const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)

onMounted(async () => {
  console.log('HomePage mounted - isAuthenticated:', authStore.isAuthenticated)
  await fetchUpcomingSessions()
  if (isAuthenticated.value) {
    await fetchUserRegistrations()
  }
})

const fetchUpcomingSessions = async () => {
  loading.value = true
  error.value = null
  console.log('Fetching sessions...')
  try {
    const sessions = await sessionStore.fetchSessions({ 
      is_active: true,
      limit: 6 
    })
    console.log('Sessions fetched:', sessions)
    upcomingSessions.value = sessions.slice(0, 6)
  } catch (err) {
    console.error('Session fetch error:', err)
    error.value = 'Failed to load sessions'
  } finally {
    loading.value = false
  }
}

const fetchUserRegistrations = async () => {
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
      // Get user's registrations
      const registrationsResponse = await axios.get(`${API_URL}/registrations`, {
        params: { player_id: userPlayer._id },
        headers: { 'Authorization': `Bearer ${token}` }
      })
      
      const registrations = registrationsResponse.data.data || registrationsResponse.data
      userRegistrations.value = Array.isArray(registrations) ? registrations : []
    }
  } catch (err) {
    console.error('Error fetching user registrations:', err)
  }
}

const isSessionRegistered = (sessionId) => {
  return userRegistrations.value.some(
    r => r.session_id === sessionId && r.status === 'registered'
  )
}

const handleRegister = (sessionId) => {
  console.log('handleRegister called, isAuthenticated:', isAuthenticated.value)
  if (!isAuthenticated.value) {
    console.log('Redirecting to login from handleRegister')
    router.push('/auth/login')
  } else {
    router.push(`/sessions/${sessionId}`)
  }
}
</script>

<style scoped>
/* Background grid pattern */
.bg-grid-white\/\[0\.05\] {
  background-image: linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
}
</style>
