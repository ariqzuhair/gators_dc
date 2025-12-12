import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import { useAuthStore } from './stores/auth'
import lazyloadDirective from './directives/lazyload'
import './assets/main.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Register lazy loading directive globally
app.directive('lazy', lazyloadDirective)

// Initialize auth state from localStorage
const authStore = useAuthStore()
authStore.checkAuth()

app.mount('#app')
