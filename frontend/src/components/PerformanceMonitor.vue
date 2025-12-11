<template>
  <div v-if="showMetrics && isDev" class="fixed bottom-4 right-4 bg-black bg-opacity-90 text-white p-4 rounded-lg shadow-lg z-50 text-xs font-mono max-w-xs">
    <div class="flex justify-between items-center mb-2">
      <h4 class="font-bold">Performance</h4>
      <button @click="showMetrics = false" class="text-gray-400 hover:text-white">✕</button>
    </div>
    
    <div class="space-y-1">
      <div class="flex justify-between">
        <span>FPS:</span>
        <span :class="getFpsColor(fps)">{{ fps }}</span>
      </div>
      <div class="flex justify-between">
        <span>Load Time:</span>
        <span>{{ loadTime }}ms</span>
      </div>
      <div class="flex justify-between">
        <span>Memory:</span>
        <span>{{ memoryUsage }}</span>
      </div>
      <div class="flex justify-between">
        <span>API Calls:</span>
        <span>{{ apiCallCount }}</span>
      </div>
      <div class="flex justify-between">
        <span>Cache Hits:</span>
        <span class="text-green-400">{{ cacheHits }}</span>
      </div>
    </div>
  </div>
  
  <button 
    v-else-if="isDev"
    @click="showMetrics = true"
    class="fixed bottom-4 right-4 bg-black bg-opacity-70 text-white p-2 rounded-full shadow-lg z-50 hover:bg-opacity-90"
    title="Show Performance Metrics"
  >
    📊
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const showMetrics = ref(false)
const isDev = import.meta.env.DEV
const fps = ref(0)
const loadTime = ref(0)
const memoryUsage = ref('0 MB')
const apiCallCount = ref(0)
const cacheHits = ref(0)

let frameCount = 0
let lastTime = performance.now()
let fpsInterval

// Track FPS
function updateFPS() {
  const currentTime = performance.now()
  frameCount++
  
  if (currentTime >= lastTime + 1000) {
    fps.value = Math.round((frameCount * 1000) / (currentTime - lastTime))
    frameCount = 0
    lastTime = currentTime
  }
  
  requestAnimationFrame(updateFPS)
}

// Track memory usage
function updateMemory() {
  if (performance.memory) {
    const used = performance.memory.usedJSHeapSize / 1048576
    memoryUsage.value = used.toFixed(2) + ' MB'
  }
}

// Get FPS color
function getFpsColor(fps) {
  if (fps >= 55) return 'text-green-400'
  if (fps >= 30) return 'text-yellow-400'
  return 'text-red-400'
}

// Track page load time
onMounted(() => {
  if (!isDev) return
  
  // Get page load time
  const perfData = performance.timing
  loadTime.value = perfData.loadEventEnd - perfData.navigationStart
  
  // Start FPS tracking
  requestAnimationFrame(updateFPS)
  
  // Update memory every 2 seconds
  fpsInterval = setInterval(() => {
    updateMemory()
  }, 2000)
  
  // Intercept fetch to count API calls
  const originalFetch = window.fetch
  window.fetch = function(...args) {
    apiCallCount.value++
    return originalFetch.apply(this, args).then(response => {
      // Check if response came from cache
      if (response.headers.get('X-Cache') === 'HIT') {
        cacheHits.value++
      }
      return response
    })
  }
})

onUnmounted(() => {
  if (fpsInterval) {
    clearInterval(fpsInterval)
  }
})
</script>
