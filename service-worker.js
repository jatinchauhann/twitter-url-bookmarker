let version = '0.1';

self.addEventListener('install', e => {
  let timeStamp = Date.now();
  e.waitUntil(
    caches.open('emitra').then(cache => {
      return cache.addAll([
        `/`,
        `/index.html?timestamp=${timeStamp}`,
        `/dash.php?timestamp=${timeStamp}`,
        `/css/main.css?timestamp=${timeStamp}`,
        `/css/style.css?timestamp=${timeStamp}`,
        `/scripts/main.js?timestamp=${timeStamp}`,
        `/images/firebase-logo.png?timestamp=${timeStamp}`,
        `/images/12.jpg?timestamp=${timeStamp}`,
        `/images/LOGO128.png?timestamp=${timeStamp}`,
        `/images/LOGO256.png?timestamp=${timeStamp}`,
        `/images/LOGO512.png?timestamp=${timeStamp}`      
      ])
      .then(() => self.skipWaiting());
    })
  )
});

self.addEventListener('activate',  event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request, {ignoreSearch:true}).then(response => {
      return response || fetch(event.request);
    })
  );
});



