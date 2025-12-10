<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-4">Available Sessions</h1>
      
      <!-- Filters -->
      <div class="card mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="label">Session Type</label>
            <select v-model="filters.type" class="input">
              <option value="">All Types</option>
              <option value="drop-in">Drop-in</option>
              <option value="training">Training</option>
              <option value="tournament">Tournament</option>
              <option value="special-event">Special Event</option>
            </select>
          </div>

          <div>
            <label class="label">Start Date</label>
            <input v-model="filters.start_date" type="date" class="input" />
          </div>

          <div class="flex items-end">
            <button @click="applyFilters" class="btn btn-primary w-full">
              Apply Filters
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>

    <div v-else-if="error" class="text-center py-12 text-red-600">
      {{ error }}
    </div>

    <div v-else-if="sessions.length === 0" class="text-center py-12 text-gray-600">
      No sessions found. Try adjusting your filters.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
