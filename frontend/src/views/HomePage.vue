<template>
  <div>
    <div class="hero bg-gradient-to-r from-primary-600 to-primary-700 text-white">
      <div class="container mx-auto px-4 py-20">
        <div class="max-w-3xl">
          <h1 class="text-5xl font-bold mb-6">Welcome to Gators Dodgeball Club</h1>
          <p class="text-xl mb-8 text-primary-100">
            Join the most exciting dodgeball community! Register for drop-in sessions, 
            training programs, and competitive tournaments.
          </p>
          <div class="flex gap-4">
            <router-link to="/sessions" class="btn bg-white text-primary-600 hover:bg-gray-100">
              View Sessions
            </router-link>
            <router-link to="/auth/register" class="btn bg-primary-500 text-white hover:bg-primary-700">
              Join Now
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-16">
      <!-- Features Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="text-center">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-2">Flexible Sessions</h3>
          <p class="text-gray-600">Drop-in sessions, training programs, and tournaments available</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-2">Great Community</h3>
          <p class="text-gray-600">Join a welcoming community of dodgeball enthusiasts</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-2">All Skill Levels</h3>
          <p class="text-gray-600">From beginners to advanced players, everyone is welcome</p>
        </div>
      </div>

      <!-- Upcoming Sessions -->
      <div>
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold">Upcoming Sessions</h2>
          <router-link to="/sessions" class="text-primary-600 hover:text-primary-700 font-medium">
            View All →
          </router-link>
        </div>

        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        </div>

        <div v-else-if="error" class="text-center py-12 text-red-600">
          {{ error }}
        </div>

        <div v-else-if="upcomingSessions.length === 0" class="text-center py-12 text-gray-600">
          No upcoming sessions at the moment.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <SessionCard 
            v-for="session in upcomingSessions" 
            :key="session._id"
            :session="session"
            :show-register-button="isAuthenticated"
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

const router = useRouter()
const sessionStore = useSessionStore()
const authStore = useAuthStore()

const upcomingSessions = ref([])
const loading = ref(false)
const error = ref(null)

const isAuthenticated = computed(() => authStore.isAuthenticated)

onMounted(async () => {
  console.log('HomePage mounted - isAuthenticated:', authStore.isAuthenticated)
  await fetchUpcomingSessions()
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
.hero {
  min-height: 400px;
  display: flex;
  align-items: center;
}
</style>
