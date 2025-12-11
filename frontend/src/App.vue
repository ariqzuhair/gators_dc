<template>
  <div id="app">
    <router-view />
    <PerformanceMonitor />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import PerformanceMonitor from '@/components/PerformanceMonitor.vue'

const authStore = useAuthStore()

onMounted(() => {
  // Initialize auth state from localStorage
  authStore.checkAuth()
})
</script>

<style scoped>
#app {
  min-height: 100vh;
}
</style>
