import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useSessionStore = defineStore('session', () => {
  const sessions = ref([])
  const currentSession = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function fetchSessions(filters = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/sessions', { params: filters })
      sessions.value = response.data.data || response.data
      return sessions.value
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchSessionById(id) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/sessions/${id}`)
      currentSession.value = response.data
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
      sessions.value.unshift(response.data.session)
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
      const index = sessions.value.findIndex(s => s._id === id)
      if (index !== -1) {
        sessions.value[index] = response.data.session
      }
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
      sessions.value = sessions.value.filter(s => s._id !== id)
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
