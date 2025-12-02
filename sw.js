// Service Worker for ProPlayHub PWA
const CACHE_NAME = 'proplayhub-v1';
const urlsToCache = [
  '/ProPlayHub/',
  '/ProPlayHub/index.html',
  '/ProPlayHub/Templates/Users/userProfile.html.php',
  '/ProPlayHub/Templates/Users/userStore.html.php',
  '/ProPlayHub/Templates/Users/userCheckout.html.php',
  'https://cdn.tailwindcss.com',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap'
];

// Install Event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
      .then(() => self.skipWaiting())
  );
});

// Activate Event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event - Network first, then cache
self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') {
    return; // Skip non-GET requests
  }

  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Don't cache non-successful responses
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }

        // Clone the response
        const responseClone = response.clone();

        // Cache successful responses
        caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, responseClone);
        });

        return response;
      })
      .catch(() => {
        // Return cached version if offline
        return caches.match(event.request)
          .then(response => {
            return response || new Response(
              '<h1>Offline</h1><p>This page is not available offline. Please check your internet connection.</p>',
              { 
                headers: { 'Content-Type': 'text/html' },
                status: 503,
                statusText: 'Service Unavailable'
              }
            );
          });
      })
  );
});

// Background Sync for form submissions
self.addEventListener('sync', event => {
  if (event.tag === 'sync-orders') {
    event.waitUntil(syncOrders());
  }
});

async function syncOrders() {
  try {
    const orders = localStorage.getItem('pending-orders');
    if (orders) {
      // Attempt to sync with server
      const response = await fetch('/ProPlayHub/sync-orders', {
        method: 'POST',
        body: orders,
        headers: { 'Content-Type': 'application/json' }
      });

      if (response.ok) {
        localStorage.removeItem('pending-orders');
      }
    }
  } catch (error) {
    console.error('Sync failed:', error);
  }
}

// Push Notifications
self.addEventListener('push', event => {
  const options = {
    body: event.data ? event.data.text() : 'New notification from ProPlayHub',
    icon: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Notification',
    badge: 'https://api.dicebear.com/7.x/shapes/svg?seed=ProPlay',
    tag: 'proplayhub-notification',
    requireInteraction: false
  };

  event.waitUntil(
    self.registration.showNotification('ProPlayHub', options)
  );
});

// Notification Click Handler
self.addEventListener('notificationclick', event => {
  event.notification.close();
  event.waitUntil(
    clients.matchAll({ type: 'window' }).then(clientList => {
      for (let client of clientList) {
        if (client.url === '/' && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow('/ProPlayHub/');
      }
    })
  );
});
