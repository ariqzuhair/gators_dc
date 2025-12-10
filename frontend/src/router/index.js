import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: MainLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: () => import('@/views/HomePage.vue')
        },
        {
          path: 'sessions',
          name: 'sessions',
          component: () => import('@/views/SessionsPage.vue')
        },
        {
          path: 'sessions/:id',
          name: 'session-detail',
          component: () => import('@/views/SessionDetailPage.vue')
        },
        {
          path: 'players',
          name: 'players',
          component: () => import('@/views/PlayersPage.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'profile',
          name: 'profile',
          component: () => import('@/views/ProfilePage.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'admin',
          name: 'admin',
          component: () => import('@/views/AdminDashboard.vue'),
          meta: { requiresAuth: true, requiresAdmin: true }
        }
      ]
    },
    {
      path: '/auth',
      component: AuthLayout,
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('@/views/auth/LoginPage.vue')
        },
        {
          path: 'register',
          name: 'register',
          component: () => import('@/views/auth/RegisterPage.vue')
        }
      ]
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // Debug logging
  const tokenInStore = authStore.token
  const tokenInStorage = localStorage.getItem('token')
  const isAuth = authStore.isAuthenticated
  
  console.log('=== Navigation Guard ===')
  console.log('Going to:', to.name)
  console.log('Requires auth:', to.meta.requiresAuth)
  console.log('Token in store:', tokenInStore ? 'YES' : 'NO')
  console.log('Token in localStorage:', tokenInStorage ? 'YES' : 'NO')
  console.log('isAuthenticated:', isAuth)
  console.log('=====================')
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    console.log('Redirecting to login - not authenticated')
    next({ name: 'login' })
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    console.log('Redirecting to home - not admin')
    next({ name: 'home' })
  } else {
    console.log('Allowing navigation')
    next()
  }
})

export default router
