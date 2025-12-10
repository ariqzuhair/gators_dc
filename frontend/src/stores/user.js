import { defineStore } from 'pinia'
import api from '@/services/api'

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [],
    currentUser: null,
    loading: false,
    error: null
  }),

  getters: {
    activeUsers: (state) => state.users.filter(user => user.is_active),
    inactiveUsers: (state) => state.users.filter(user => !user.is_active),
    adminUsers: (state) => state.users.filter(user => user.role === 'admin'),
    playerUsers: (state) => state.users.filter(user => user.role === 'player'),
    totalUsers: (state) => state.users.length
  },

  actions: {
    async fetchUsers() {
      this.loading = true
      this.error = null
      try {
        const response = await api.get('/users')
        this.users = response.data.users
        return response.data.users
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch users'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchUser(userId) {
      this.loading = true
      this.error = null
      try {
        const response = await api.get(`/users/${userId}`)
        this.currentUser = response.data.user
        return response.data.user
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch user'
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateUser(userId, userData) {
      this.loading = true
      this.error = null
      try {
        const response = await api.put(`/users/${userId}`, userData)
        
        // Update the user in the local state
        const index = this.users.findIndex(u => u._id === userId)
        if (index !== -1) {
          this.users[index] = response.data.user
        }
        
        return response.data.user
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update user'
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteUser(userId) {
      this.loading = true
      this.error = null
      try {
        await api.delete(`/users/${userId}`)
        
        // Remove the user from the local state
        this.users = this.users.filter(u => u._id !== userId)
        
        return true
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete user'
        throw error
      } finally {
        this.loading = false
      }
    },

    async toggleUserStatus(userId) {
      const user = this.users.find(u => u._id === userId)
      if (!user) {
        throw new Error('User not found')
      }

      return this.updateUser(userId, {
        is_active: !user.is_active
      })
    },

    clearError() {
      this.error = null
    }
  }
})
