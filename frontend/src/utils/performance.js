// Performance utility functions

/**
 * Debounce function to limit API calls
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
export function debounce(func, wait = 300) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

/**
 * Throttle function to limit execution frequency
 * @param {Function} func - Function to throttle
 * @param {number} limit - Time limit in milliseconds
 * @returns {Function} Throttled function
 */
export function throttle(func, limit = 300) {
  let inThrottle
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args)
      inThrottle = true
      setTimeout(() => inThrottle = false, limit)
    }
  }
}

/**
 * Lazy load images using Intersection Observer
 * @param {Element} img - Image element to lazy load
 */
export function lazyLoadImage(img) {
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const image = entry.target
        image.src = image.dataset.src
        image.classList.remove('lazy')
        observer.unobserve(image)
      }
    })
  })
  observer.observe(img)
}

/**
 * Cache API responses in memory
 */
class ApiCache {
  constructor(ttl = 5 * 60 * 1000) { // Default 5 minutes
    this.cache = new Map()
    this.ttl = ttl
  }

  set(key, value) {
    const expiry = Date.now() + this.ttl
    this.cache.set(key, { value, expiry })
  }

  get(key) {
    const item = this.cache.get(key)
    if (!item) return null
    
    if (Date.now() > item.expiry) {
      this.cache.delete(key)
      return null
    }
    
    return item.value
  }

  clear() {
    this.cache.clear()
  }

  has(key) {
    const item = this.cache.get(key)
    if (!item) return false
    
    if (Date.now() > item.expiry) {
      this.cache.delete(key)
      return false
    }
    
    return true
  }
}

export const apiCache = new ApiCache()

/**
 * Format date efficiently (memoized)
 */
const dateFormatCache = new Map()

export function formatDateCached(dateString, options) {
  const cacheKey = `${dateString}-${JSON.stringify(options)}`
  
  if (dateFormatCache.has(cacheKey)) {
    return dateFormatCache.get(cacheKey)
  }
  
  const formatted = new Date(dateString).toLocaleDateString('en-US', options)
  dateFormatCache.set(cacheKey, formatted)
  
  return formatted
}

/**
 * Batch API requests to reduce network calls
 */
export class RequestBatcher {
  constructor(batchFn, delay = 50) {
    this.batchFn = batchFn
    this.delay = delay
    this.queue = []
    this.timer = null
  }

  add(request) {
    return new Promise((resolve, reject) => {
      this.queue.push({ request, resolve, reject })
      
      if (!this.timer) {
        this.timer = setTimeout(() => {
          this.flush()
        }, this.delay)
      }
    })
  }

  async flush() {
    if (this.queue.length === 0) return
    
    const batch = [...this.queue]
    this.queue = []
    this.timer = null
    
    try {
      const results = await this.batchFn(batch.map(item => item.request))
      batch.forEach((item, index) => {
        item.resolve(results[index])
      })
    } catch (error) {
      batch.forEach(item => item.reject(error))
    }
  }
}

/**
 * Virtual scroll helper for large lists
 */
export function useVirtualScroll(items, itemHeight, containerHeight) {
  const visibleCount = Math.ceil(containerHeight / itemHeight)
  const bufferCount = 5 // Extra items to render for smooth scrolling
  
  return {
    getVisibleItems(scrollTop) {
      const startIndex = Math.max(0, Math.floor(scrollTop / itemHeight) - bufferCount)
      const endIndex = Math.min(items.length, startIndex + visibleCount + bufferCount * 2)
      
      return {
        items: items.slice(startIndex, endIndex),
        startIndex,
        offsetY: startIndex * itemHeight
      }
    },
    totalHeight: items.length * itemHeight
  }
}
