/**
 * Lazy Loading Directive for Images
 * 
 * Usage:
 * <img v-lazy="imageUrl" alt="description" />
 * 
 * Features:
 * - Intersection Observer for efficient loading
 * - Blur-up placeholder effect
 * - Error handling with fallback image
 * - Loading animation
 */

export default {
  mounted(el, binding) {
    const imageUrl = binding.value
    
    // Add loading class
    el.classList.add('lazy-loading')
    
    // Set low-quality placeholder or loading state
    if (!el.src) {
      el.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f3f4f6" width="400" height="300"/%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" fill="%239ca3af" font-family="sans-serif" font-size="18"%3ELoading...%3C/text%3E%3C/svg%3E'
    }
    
    // Intersection Observer options
    const options = {
      root: null,
      rootMargin: '50px', // Start loading 50px before element is visible
      threshold: 0.01
    }
    
    // Create observer
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target
          
          // Create a new image to preload
          const tempImg = new Image()
          
          tempImg.onload = () => {
            // Image loaded successfully
            img.src = imageUrl
            img.classList.remove('lazy-loading')
            img.classList.add('lazy-loaded')
          }
          
          tempImg.onerror = () => {
            // Error loading image - use fallback
            img.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23fee2e2" width="400" height="300"/%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" fill="%23dc2626" font-family="sans-serif" font-size="18"%3EImage Unavailable%3C/text%3E%3C/svg%3E'
            img.classList.remove('lazy-loading')
            img.classList.add('lazy-error')
          }
          
          // Start loading the actual image
          tempImg.src = imageUrl
          
          // Stop observing this element
          observer.unobserve(img)
        }
      })
    }, options)
    
    // Start observing
    observer.observe(el)
    
    // Store observer on element for cleanup
    el._lazyObserver = observer
  },
  
  unmounted(el) {
    // Cleanup observer
    if (el._lazyObserver) {
      el._lazyObserver.disconnect()
      delete el._lazyObserver
    }
  }
}
