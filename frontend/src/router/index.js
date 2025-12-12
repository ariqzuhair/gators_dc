import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

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
          // Lazy load with prefetch for faster navigation
          component: () => import(/* webpackChunkName: "home", webpackPrefetch: true */ '@/views/HomePage.vue')
        },
        {
          path: 'sessions',
          name: 'sessions',
          // Lazy load - frequently accessed
          component: () => import(/* webpackChunkName: "sessions", webpackPrefetch: true */ '@/views/SessionsPage.vue')
        },
        {
          path: 'sessions/:id',
          name: 'session-detail',
          // Lazy load with preload (needed after sessions page)
          component: () => import(/* webpackChunkName: "session-detail", webpackPreload: true */ '@/views/SessionDetailPage.vue')
        },
        {
          path: 'players',
          name: 'players',
          component: () => import(/* webpackChunkName: "players" */ '@/views/PlayersPage.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'profile',
          name: 'profile',
          component: () => import(/* webpackChunkName: "profile", webpackPrefetch: true */ '@/views/ProfilePage.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'merchandise',
          name: 'merchandise',
          component: () => import(/* webpackChunkName: "merchandise", webpackPrefetch: true */ '@/views/MerchandisePage.vue')
        }
      ]
    },
    {
      path: '/admin',
      component: AdminLayout,
      children: [
        {
          path: '',
          name: 'admin',
          component: () => import(/* webpackChunkName: "admin-dashboard" */ '@/views/AdminDashboard.vue'),
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
          component: () => import(/* webpackChunkName: "auth-login" */ '@/views/auth/LoginPage.vue')
        },
        {
          path: 'register',
          name: 'register',
          component: () => import(/* webpackChunkName: "auth-register" */ '@/views/auth/RegisterPage.vue')
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
  const userInStorage = localStorage.getItem('user')
  const isAuth = authStore.isAuthenticated
  const isAdminUser = authStore.isAdmin
  
  console.log('=== Navigation Guard ===')
  console.log('Going to:', to.name)
  console.log('From:', from.name)
  console.log('Requires auth:', to.meta.requiresAuth)
  console.log('Requires admin:', to.meta.requiresAdmin)
  console.log('Token in store:', tokenInStore ? 'YES' : 'NO')
  console.log('Token in localStorage:', tokenInStorage ? 'YES' : 'NO')
  console.log('User in localStorage:', userInStorage ? 'YES' : 'NO')
  console.log('isAuthenticated:', isAuth)
  console.log('isAdmin:', isAdminUser)
  console.log('User role:', authStore.user?.role)
  console.log('=====================')
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    console.log('Redirecting to login - not authenticated')
    next({ name: 'login' })
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    console.log('Redirecting to home - not admin, role:', authStore.user?.role)
    next({ name: 'home' })
  } else {
    console.log('Allowing navigation')
    next()
  }
})

export default router
