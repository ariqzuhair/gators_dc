// Service Worker for PWA caching and offline support
const CACHE_NAME = 'gators-dc-v1'
const RUNTIME_CACHE = 'gators-runtime-v1'
const API_CACHE = 'gators-api-v1'

// Assets to cache immediately
const PRECACHE_ASSETS = [
  '/',
  '/index.html',
  '/manifest.json'
]

// Install event - cache critical assets
self.addEventListener('install', (event) => {
  console.log('Service Worker: Installing...')
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Service Worker: Precaching assets')
        return cache.addAll(PRECACHE_ASSETS)
      })
      .then(() => self.skipWaiting())
  )
})

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('Service Worker: Activating...')
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE && cacheName !== API_CACHE) {
            console.log('Service Worker: Deleting old cache:', cacheName)
            return caches.delete(cacheName)
          }
        })
      )
    }).then(() => self.clients.claim())
  )
})

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
  const { request } = event
  const url = new URL(request.url)
  
  // Skip cross-origin requests
  if (url.origin !== location.origin && !url.href.includes('/api/')) {
    return
  }
  
  // API requests - Network first with cache fallback
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      caches.open(API_CACHE).then((cache) => {
        return fetch(request)
          .then((response) => {
            // Only cache successful GET requests
            if (request.method === 'GET' && response.ok) {
              cache.put(request, response.clone())
            }
            return response
          })
          .catch(() => {
            // Fallback to cache if network fails
            return cache.match(request).then((cached) => {
              if (cached) {
                console.log('Service Worker: Serving API from cache:', url.pathname)
                return cached
              }
              return new Response(JSON.stringify({ error: 'Offline' }), {
                status: 503,
                headers: { 'Content-Type': 'application/json' }
              })
            })
          })
      })
    )
    return
  }
  
  // Static assets - Cache first, fallback to network
  if (request.destination === 'style' || request.destination === 'script' || request.destination === 'image') {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) {
          console.log('Service Worker: Serving from cache:', url.pathname)
          return cached
        }
        
        return fetch(request).then((response) => {
          return caches.open(RUNTIME_CACHE).then((cache) => {
            if (response.ok) {
              cache.put(request, response.clone())
            }
            return response
          })
        })
      })
    )
    return
  }
  
  // HTML pages - Network first with cache fallback
  event.respondWith(
    fetch(request)
      .then((response) => {
        return caches.open(RUNTIME_CACHE).then((cache) => {
          if (response.ok) {
            cache.put(request, response.clone())
          }
          return response
        })
      })
      .catch(() => {
        return caches.match(request).then((cached) => {
          if (cached) {
            console.log('Service Worker: Serving page from cache:', url.pathname)
            return cached
          }
          return caches.match('/index.html')
        })
      })
  )
})

// Background sync for offline actions (future enhancement)
self.addEventListener('sync', (event) => {
  console.log('Service Worker: Background sync:', event.tag)
  
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData())
  }
})

async function syncData() {
  // Implement background sync logic
  console.log('Service Worker: Syncing data...')
}

// Push notifications (future enhancement)
self.addEventListener('push', (event) => {
  const data = event.data?.json() || {}
  
  const options = {
    body: data.body || 'New notification from Gators DC',
    icon: '/logo.png',
    badge: '/badge.png',
    vibrate: [200, 100, 200],
    data: data
  }
  
  event.waitUntil(
    self.registration.showNotification(data.title || 'Gators DC', options)
  )
})

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  event.notification.close()
  
  event.waitUntil(
    clients.openWindow(event.notification.data.url || '/')
  )
})
