<template>
  <div class="card hover:shadow-xl transition-shadow duration-300">
    <div class="mb-4">
      <div class="flex-1">
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ session.title }}</h3>
        <div class="flex items-center space-x-2 text-sm text-gray-600">
          <span class="badge" :class="getTypeBadgeClass(session.type)">
            {{ session.type }}
          </span>
        </div>
      </div>
    </div>

    <p v-if="session.description" class="text-gray-600 mb-4">{{ session.description }}</p>

    <div class="space-y-2 mb-4">
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        {{ formatDate(session.date) }}
      </div>
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}
      </div>
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        {{ session.location }}
      </div>
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        {{ session.current_participants }} / {{ session.max_participants }} participants
      </div>
    </div>

    <div class="flex gap-2">
      <router-link 
        :to="`/sessions/${session._id}`" 
        class="btn btn-primary flex-1"
      >
        View Details
      </router-link>
      <button 
        v-if="showRegisterButton && !isRegistered"
        @click="$emit('register', session._id)"
        :disabled="isFull"
        class="btn flex-1"
        :class="isFull ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary'"
      >
        {{ isFull ? 'Full' : 'Register' }}
      </button>
      <div 
        v-else-if="isRegistered"
        class="flex-1 px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold text-center flex items-center justify-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Registered
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  session: {
    type: Object,
    required: true
  },
  showRegisterButton: {
    type: Boolean,
    default: true
  },
  isRegistered: {
    type: Boolean,
    default: false
  }
})

defineEmits(['register'])

const isFull = computed(() => {
  return props.session.current_participants >= props.session.max_participants
})

const getTypeBadgeClass = (type) => {
  const classes = {
    'drop-in': 'badge-blue',
    'training': 'badge-green',
    'tournament': 'badge-red',
    'special-event': 'badge-purple'
  }
  return classes[type] || 'badge-gray'
}

const getLevelBadgeClass = (level) => {
  const classes = {
    'beginner': 'badge-green',
    'intermediate': 'badge-yellow',
    'advanced': 'badge-red',
    'all': 'badge-gray'
  }
  return classes[level] || 'badge-gray'
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    weekday: 'short', 
    year: 'numeric', 
    month: 'short', 
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
</script>

<style scoped>
.badge {
  @apply px-2 py-1 rounded-full text-xs font-medium;
}

.badge-blue {
  @apply bg-blue-100 text-blue-800;
}

.badge-green {
  @apply bg-green-100 text-green-800;
}

.badge-yellow {
  @apply bg-yellow-100 text-yellow-800;
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
