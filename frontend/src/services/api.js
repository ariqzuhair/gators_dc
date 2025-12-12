import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true // Enable cookies for Sanctum
})

// Request interceptor - no longer needed for token
api.interceptors.request.use(
  (config) => {
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', {
      status: error.response?.status,
      url: error.config?.url,
      message: error.response?.data?.message,
      headers: error.config?.headers
    })
    
    // Only redirect on 401 if we're NOT on the login page
    // Let the login page handle its own 401 errors
    if (error.response?.status === 401 && !error.config?.url?.includes('/login')) {
      console.log('401 Unauthorized - Redirecting to login')
      // Clear any stored data
      localStorage.removeItem('user')
      window.location.href = '/auth/login'
    }
    
    return Promise.reject(error)
  }
)

export default api
