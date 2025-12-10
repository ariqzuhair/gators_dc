<template>
  <nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
            <span class="text-white font-bold text-xl">G</span>
          </div>
          <span class="text-xl font-bold text-gray-800">Gators DC</span>
        </router-link>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link to="/" class="nav-link" exact>Home</router-link>
          <router-link to="/sessions" class="nav-link">Sessions</router-link>
          <router-link to="/merchandise" class="nav-link">Merchandise</router-link>
        </div>

        <!-- Auth Buttons -->
        <div class="hidden md:flex items-center space-x-4">
          <template v-if="isAuthenticated">
            <router-link to="/profile" class="nav-link">
              {{ user?.name }}
            </router-link>
            <button @click="handleLogout" class="btn btn-secondary">
              Logout
            </button>
          </template>
          <template v-else>
            <router-link to="/auth/login" class="btn btn-secondary">
              Login
            </router-link>
            <router-link to="/auth/register" class="btn btn-primary">
              Register
            </router-link>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t">
        <div class="flex flex-col space-y-4">
          <router-link to="/" class="nav-link-mobile" exact>Home</router-link>
          <router-link to="/sessions" class="nav-link-mobile">Sessions</router-link>
          <router-link to="/merchandise" class="nav-link-mobile">Merchandise</router-link>
          
          <div class="pt-4 border-t">
            <template v-if="isAuthenticated">
              <router-link to="/profile" class="nav-link-mobile">Profile</router-link>
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
  @apply text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200 relative;
}

.nav-link-mobile {
  @apply text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200 py-2 relative;
}

.router-link-exact-active.nav-link {
  @apply text-primary-600;
}

.router-link-exact-active.nav-link::after {
  content: '';
  @apply absolute bottom-0 left-0 w-full h-0.5 bg-primary-600;
}

.router-link-exact-active.nav-link-mobile {
  @apply text-primary-600 bg-primary-50 rounded-lg px-3;
}
</style>
