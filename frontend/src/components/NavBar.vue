<template>
  <nav class="bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4 lg:px-6">
      <div class="flex justify-between items-center h-18">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-3 group">
          <div class="w-11 h-11 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transform group-hover:scale-105 transition-all duration-300">
            <span class="text-white font-bold text-xl">G</span>
          </div>
          <span class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Gators DC</span>
        </router-link>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-2">
          <router-link to="/" class="nav-link" exact>Home</router-link>
          <router-link to="/sessions" class="nav-link">Sessions</router-link>
          <router-link to="/merchandise" class="nav-link">Merchandise</router-link>
          <router-link v-if="isAdmin" to="/admin" class="nav-link">
            Admin Panel
          </router-link>
        </div>

        <!-- Auth Buttons -->
        <div class="hidden md:flex items-center space-x-3">
          <template v-if="isAuthenticated">
            <router-link v-if="!isAdmin" to="/profile" class="nav-link-profile">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              {{ user?.name }}
            </router-link>
            <span v-if="isAdmin" class="flex items-center space-x-2 px-3 py-2 text-gray-700 font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span>{{ user?.name }}</span>
            </span>
            <button @click="handleLogout" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 hover:shadow-md">
              Logout
            </button>
          </template>
          <template v-else>
            <router-link to="/auth/login" class="px-4 py-2 text-gray-700 hover:text-primary-600 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
              Login
            </router-link>
            <router-link to="/auth/register" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
              Register
            </router-link>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!mobileMenuOpen" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
            <path v-else strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-100 bg-white">
        <div class="flex flex-col space-y-4">
          <router-link to="/" class="nav-link-mobile" exact>Home</router-link>
          <router-link to="/sessions" class="nav-link-mobile">Sessions</router-link>
          <router-link to="/merchandise" class="nav-link-mobile">Merchandise</router-link>
          
          <div class="pt-4 border-t">
            <template v-if="isAuthenticated">
              <router-link v-if="isAdmin" to="/admin" class="nav-link-mobile">Admin Panel</router-link>
              <router-link v-if="!isAdmin" to="/profile" class="nav-link-mobile">Profile</router-link>
              <button @click="handleLogout" class="btn btn-secondary w-full mt-2">
                Logout
              </button>
            </template>
            <template v-else>
              <router-link to="/auth/login" class="btn btn-secondary w-full mb-2">
                Login
              </router-link>
              <router-link to="/auth/register" class="btn btn-primary w-full">
                Register
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const mobileMenuOpen = ref(false)

const isAuthenticated = computed(() => authStore.isAuthenticated)
const isAdmin = computed(() => authStore.isAdmin)
const user = computed(() => authStore.user)

const handleLogout = () => {
  authStore.logout()
  router.push('/auth/login')
}
</script>

<style scoped>
.nav-link {
  @apply px-4 py-2 text-gray-600 hover:text-primary-600 font-medium transition-all duration-200 relative rounded-xl hover:bg-gray-50;
}

.nav-link-profile {
  @apply flex items-center space-x-2 px-4 py-2 text-gray-700 hover:text-primary-600 font-medium transition-all duration-200 rounded-xl hover:bg-gray-50;
}

.nav-link-mobile {
  @apply text-gray-600 hover:text-primary-600 font-medium transition-all duration-200 py-3 px-4 relative rounded-xl hover:bg-gray-50;
}

.router-link-exact-active.nav-link {
  @apply text-primary-600 bg-primary-50;
}

.router-link-exact-active.nav-link::after {
  content: '';
  @apply absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-primary-600 rounded-full;
}

.router-link-exact-active.nav-link-mobile {
  @apply text-primary-600 bg-primary-50 rounded-xl;
}
</style>
