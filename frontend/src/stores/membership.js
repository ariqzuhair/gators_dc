import { defineStore } from 'pinia'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8001/api'

export const useMembershipStore = defineStore('membership', {
  state: () => ({
    memberships: [],
    semesters: [],
    loading: false,
    error: null
  }),

  getters: {
    getMembershipByUserId: (state) => (userId) => {
      return state.memberships.find(m => m.user_id === userId)
    },

    getMembershipsWithStatus: (state) => (semester, year) => {
      return state.memberships.map(membership => {
        const semesterData = membership.semester_memberships?.find(
          s => s.semester === semester && s.year === year
        )
        return {
          ...membership,
          currentSemesterStatus: semesterData?.status || 'pending',
          currentSemesterRenewed: semesterData?.renewed || false,
          currentSemesterData: semesterData || null
        }
      })
    },

    activeMemberships: (state) => {
      return state.memberships.filter(m => m.is_active)
    },

    expiredMemberships: (state) => {
      return state.memberships.filter(m => {
        const latestSemester = m.semester_memberships?.[m.semester_memberships.length - 1]
        return latestSemester?.status === 'expired'
      })
    }
  },

  actions: {
    async fetchMemberships() {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.get(`${API_URL}/memberships`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
        this.memberships = response.data.memberships
        return response.data.memberships
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch memberships'
        console.error('Error fetching memberships:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchSemesters() {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.get(`${API_URL}/memberships/semesters`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
        this.semesters = response.data.semesters
        return response.data.semesters
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch semesters'
        console.error('Error fetching semesters:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateSemesterMembership(userId, semesterData) {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.put(
          `${API_URL}/memberships/${userId}/semester`,
          semesterData,
          {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          }
        )
        
        // Update local state
        const index = this.memberships.findIndex(m => m.user_id === userId)
        if (index !== -1) {
          await this.fetchMemberships() // Refresh to get updated data
        }
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update membership'
        console.error('Error updating membership:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async renewMembership(userId, semester, year) {
      return this.updateSemesterMembership(userId, {
        semester,
        year,
        status: 'active',
        renewed: true,
        renewal_date: new Date().toISOString()
      })
    },

    async expireMembership(userId, semester, year) {
      return this.updateSemesterMembership(userId, {
        semester,
        year,
        status: 'expired',
        renewed: false,
        renewal_date: null
      })
    },

    async startSemester(semester, year) {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.post(
          `${API_URL}/memberships/start-semester`,
          { semester, year },
          {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          }
        )
        
        // Refresh memberships after starting semester
        await this.fetchMemberships()
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to start semester'
        console.error('Error starting semester:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async endSemester(semester, year) {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.post(
          `${API_URL}/memberships/end-semester`,
          { semester, year },
          {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          }
        )
        
        // Refresh memberships after ending semester
        await this.fetchMemberships()
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to end semester'
        console.error('Error ending semester:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async bulkRenewMemberships(semester, year, userIds) {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.post(
          `${API_URL}/memberships/bulk-renew`,
          { semester, year, user_ids: userIds },
          {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          }
        )
        
        // Refresh memberships after bulk renewal
        await this.fetchMemberships()
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to bulk renew memberships'
        console.error('Error bulk renewing memberships:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})
