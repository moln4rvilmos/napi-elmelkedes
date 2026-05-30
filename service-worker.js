// Cache-elési verzió megadása
const CACHE_NAME = 'vilix-cache-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/manifest.json',
  '/stilus.css',
  '/napok.js',
  '/nevnapok.js',
  '/icons/favicon-192.png',
  '/icons/favicon-512.png'
];

// Telepítési esemény, ami letölti és cache-be menti az oldal fájljait
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

// Fetch esemény, ami a gyorsítótárat használja
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
  );
});

// Régi cache-ek törlése frissítéskor
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
    })
  );
});
