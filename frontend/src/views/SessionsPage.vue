<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50/20">
    <div class="container mx-auto px-4 lg:px-6 py-6 md:py-10">
      <!-- Modern Header -->
      <div class="mb-6 md:mb-8">
        <div class="flex items-center mb-2">
          <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center mr-3 shadow-lg">
            <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-1">Available Sessions</h1>
            <p class="text-gray-600 text-sm md:text-base">Find and register for upcoming dodgeball sessions</p>
          </div>
        </div>
      </div>
      
      <!-- Modern Filters Card -->
      <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex items-center mb-4">
          <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          <h2 class="text-lg font-semibold text-gray-900">Filter Sessions</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
            <select v-model="filters.type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
              <option value="">All Types</option>
              <option value="drop-in">Drop-in</option>
              <option value="training">Training</option>
              <option value="tournament">Tournament</option>
              <option value="special-event">Special Event</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
            <input v-model="filters.start_date" type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" />
          </div>

          <div class="flex items-end">
            <button @click="applyFilters" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center group">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              Apply Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-primary-200 border-t-primary-600"></div>
        <p class="mt-4 text-gray-600 text-lg">Loading sessions...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <p class="text-red-600 text-lg">{{ error }}</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="sessions.length === 0" class="text-center py-20">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <p class="text-gray-600 text-lg">No sessions found. Try adjusting your filters.</p>
      </div>

      <!-- Sessions Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <SessionCard 
          v-for="session in sessions" 
          :key="session._id"
          :session="session"
          :show-register-button="isAuthenticated"
          :is-registered="isSessionRegistered(session._id)"
          @register="handleRegister"
        />
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

const sessions = computed(() => sessionStore.sessions)
const loading = computed(() => sessionStore.loading)
const error = computed(() => sessionStore.error)
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)

const userRegistrations = ref([])

const filters = ref({
  type: '',
  skill_level: '',
  start_date: '',
  is_active: true
})

onMounted(async () => {
  await applyFilters()
  if (isAuthenticated.value) {
    await fetchUserRegistrations()
  }
})

const applyFilters = async () => {
  const cleanFilters = Object.fromEntries(
    Object.entries(filters.value).filter(([_, v]) => v !== '')
  )
  await sessionStore.fetchSessions(cleanFilters)
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
  if (!isAuthenticated.value) {
    router.push('/auth/login')
  } else {
    router.push(`/sessions/${sessionId}`)
  }
}
</script>
