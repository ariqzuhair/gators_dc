import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'
import { apiCache } from '@/utils/performance'

export const useSessionStore = defineStore('session', () => {
  const sessions = ref([])
  const currentSession = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function fetchSessions(filters = {}) {
    // Check cache first
    const cacheKey = `sessions-${JSON.stringify(filters)}`
    const cached = apiCache.get(cacheKey)
    
    if (cached) {
      sessions.value = cached
      return cached
    }

    loading.value = true
    error.value = null
    try {
      const response = await api.get('/sessions', { params: filters })
      const data = response.data.data || response.data
      sessions.value = data
      
      // Debug: Log first session date format
      if (data.length > 0) {
        console.log('Session date format:', data[0].date, typeof data[0].date)
      }
      
      // Cache the response for 2 minutes
      apiCache.set(cacheKey, data)
      
      return data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchSessionById(id) {
    // Check cache first
    const cacheKey = `session-${id}`
    const cached = apiCache.get(cacheKey)
    
    if (cached) {
      currentSession.value = cached
      return cached
    }

    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/sessions/${id}`)
      currentSession.value = response.data
      
      // Cache the response
      apiCache.set(cacheKey, response.data)
      
      return currentSession.value
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createSession(sessionData) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/sessions', sessionData)
      
      // Clear cache and refetch to ensure proper date formatting
      apiCache.clear()
      await fetchSessions()
      
      return response.data.session
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateSession(id, sessionData) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/sessions/${id}`, sessionData)
      
      // Clear cache and refetch to ensure proper date formatting
      apiCache.clear()
      await fetchSessions()
      
      return response.data.session
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteSession(id) {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/sessions/${id}`)
      
      // Clear cache and refetch
      apiCache.clear()
      await fetchSessions()
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    sessions,
    currentSession,
    loading,
    error,
    fetchSessions,
    fetchSessionById,
    createSession,
    updateSession,
    deleteSession
  }
})
