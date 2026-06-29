const CACHE_NAME = 'agdniger-static-v1';
const STATIC_EXTENSIONS = /\.(css|js|png|jpg|jpeg|gif|webp|svg|woff2?|ttf)$/;

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const request = event.request;
  const url = new URL(request.url);

  // Never touch admin panel, the migration tool, or any non-GET request (forms, donation
  // redirects, comment posts) — those must always hit the network untouched.
  if (request.method !== 'GET' || url.pathname.includes('/admin/') || url.pathname.includes('/tools/')) {
    return;
  }

  if (STATIC_EXTENSIONS.test(url.pathname)) {
    event.respondWith(
      caches.open(CACHE_NAME).then((cache) =>
        cache.match(request).then((cached) => {
          if (cached) {
            return cached;
          }
          return fetch(request).then((response) => {
            if (response.ok) {
              cache.put(request, response.clone());
            }
            return response;
          });
        })
      )
    );
    return;
  }

  // HTML/public pages: network-first so content stays fresh, falling back to the cache
  // (if previously visited) when offline.
  event.respondWith(
    fetch(request)
      .then((response) => {
        if (response.ok) {
          caches.open(CACHE_NAME).then((cache) => cache.put(request, response.clone()));
        }
        return response;
      })
      .catch(() => caches.match(request))
  );
});
