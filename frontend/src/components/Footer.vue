<template>
  <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 lg:px-6 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <!-- About -->
        <div class="space-y-4">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center shadow-lg">
              <span class="text-white font-bold text-lg">G</span>
            </div>
            <h3 class="text-xl font-bold">Gators DC</h3>
          </div>
          <p class="text-gray-400 text-sm leading-relaxed">
            Your premier destination for dodgeball fun and competition.
            Join us for drop-in sessions, training, and tournaments!
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-bold mb-4 text-white">Quick Links</h3>
          <ul class="space-y-3 text-sm">
            <li>
              <router-link to="/" class="text-gray-400 hover:text-primary-500 transition-colors duration-200 flex items-center space-x-2 group">
                <span class="w-1.5 h-1.5 bg-primary-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span>Home</span>
              </router-link>
            </li>
            <li>
              <router-link to="/sessions" class="text-gray-400 hover:text-primary-500 transition-colors duration-200 flex items-center space-x-2 group">
                <span class="w-1.5 h-1.5 bg-primary-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span>Sessions</span>
              </router-link>
            </li>
            <li>
              <router-link to="/merchandise" class="text-gray-400 hover:text-primary-500 transition-colors duration-200 flex items-center space-x-2 group">
                <span class="w-1.5 h-1.5 bg-primary-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span>Merchandise</span>
              </router-link>
            </li>
            <li>
              <router-link to="/auth/register" class="text-gray-400 hover:text-primary-500 transition-colors duration-200 flex items-center space-x-2 group">
                <span class="w-1.5 h-1.5 bg-primary-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span>Join Now</span>
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="text-lg font-bold mb-4 text-white">Contact Us</h3>
          <ul class="space-y-3 text-sm">
            <li class="flex items-start space-x-3 text-gray-400">
              <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span>info@gatorsdc.com</span>
            </li>
            <li class="flex items-start space-x-3 text-gray-400">
              <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span>(555) 123-4567</span>
            </li>
            <li class="flex items-start space-x-3 text-gray-400">
              <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>123 Sports Ave, City</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-700/50 mt-12 pt-8 text-center text-sm text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} Gators Dodgeball Club. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
