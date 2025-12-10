<template>
  <footer class="bg-gray-800 text-white mt-auto">
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- About -->
        <div>
          <h3 class="text-lg font-bold mb-4">Gators Dodgeball Club</h3>
          <p class="text-gray-400 text-sm">
            Your premier destination for dodgeball fun and competition.
            Join us for drop-in sessions, training, and tournaments!
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-bold mb-4">Quick Links</h3>
          <ul class="space-y-2 text-sm">
            <li>
              <router-link to="/" class="text-gray-400 hover:text-white">Home</router-link>
            </li>
            <li>
              <router-link to="/sessions" class="text-gray-400 hover:text-white">Sessions</router-link>
            </li>
            <li>
              <router-link to="/auth/register" class="text-gray-400 hover:text-white">Join Now</router-link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="text-lg font-bold mb-4">Contact Us</h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li>Email: info@gatorsdc.com</li>
            <li>Phone: (555) 123-4567</li>
            <li>Location: 123 Sports Ave, City</li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} Gators Dodgeball Club. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
