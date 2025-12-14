import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)

  const isAuthenticated = computed(() => !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  async function checkAuth() {
    const storedUser = localStorage.getItem('user')
    
    if (storedUser) {
      user.value = JSON.parse(storedUser)
    }

    // Verify session with backend
    try {
      const response = await api.get('/me')
      user.value = response.data
      localStorage.setItem('user', JSON.stringify(user.value))
    } catch (error) {
      // Session expired or invalid
      user.value = null
      localStorage.removeItem('user')
    }
  }

  async function login(credentials) {
    try {
      // Get CSRF cookie first
      await axios.get('http://localhost:8001/sanctum/csrf-cookie', { withCredentials: true })
      
      // Then login
      const response = await api.post('/login', credentials)
      user.value = response.data.user
      
      localStorage.setItem('user', JSON.stringify(user.value))
      
      return response.data
    } catch (error) {
      throw error
    }
  }

  async function register(userData) {
    try {
      // Get CSRF cookie first
      await axios.get('http://localhost:8001/sanctum/csrf-cookie', { withCredentials: true })
      
      // Only send fields that the API expects
      const registrationData = {
        name: userData.name,
        email: userData.email,
        password: userData.password,
        phone: userData.phone,
        role: userData.role
      }
      
      const response = await api.post('/register', registrationData)
      
      user.value = response.data.user
      localStorage.setItem('user', JSON.stringify(user.value))
      // User will login with their credentials
      
      return response.data
    } catch (error) {
      throw error
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      user.value = null
      localStorage.removeItem('user')
    }
  }

  return {
    user,
    isAuthenticated,
    isAdmin,
    checkAuth,
    login,
    register,
    logout
  }
})
