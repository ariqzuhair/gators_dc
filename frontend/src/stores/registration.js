import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useRegistrationStore = defineStore('registration', () => {
  const registrations = ref([])
  const loading = ref(false)
  const error = ref(null)

  async function fetchRegistrations(filters = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/registrations', { params: filters })
      registrations.value = response.data.data || response.data
      return registrations.value
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function registerForSession(playerId, sessionId) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/registrations', {
        player_id: playerId,
        session_id: sessionId
      })
      registrations.value.unshift(response.data.registration)
      return response.data.registration
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function cancelRegistration(id) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post(`/registrations/${id}/cancel`)
      const index = registrations.value.findIndex(r => r._id === id)
      if (index !== -1) {
        registrations.value[index] = response.data.registration
      }
      return response.data.registration
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    registrations,
    loading,
    error,
    fetchRegistrations,
    registerForSession,
    cancelRegistration
  }
})
