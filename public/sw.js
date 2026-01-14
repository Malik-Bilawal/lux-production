// public/sw.js
const CACHE_NAME = "gymsite-v1";
const urlsToCache = [
  "/",
  "/manifest.json",
  "/icons/icon-196x196.png",
  "/icons/icon-512x512.png"
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return Promise.all(
        urlsToCache.map((url) => 
          cache.add(url).catch(err => {
            console.warn("Failed to cache:", url, err);
            return null; 
          })
        )
      );
    })
  );
  self.skipWaiting();
});


self.addEventListener("activate", (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((resp) => resp || fetch(event.request))
  );
});




