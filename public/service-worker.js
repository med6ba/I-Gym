const CACHE_NAME = 'igym-pwa-v3';

const PRECACHE_URLS = [
    '/',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        ).then(() => self.clients.matchAll()).then((clients) =>
            clients.forEach((client) => client.postMessage({ type: 'SW_UPDATED' }))
        )
    );
    self.clients.claim();
});

self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    if (
        request.method !== 'GET' ||
        url.protocol === 'chrome-extension:' ||
        url.origin !== self.location.origin
    ) {
        return;
    }

    if (url.pathname.startsWith('/build/')) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetchAndCache(request))
        );
        return;
    }

    if (url.pathname.startsWith('/icons/')) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetchAndCache(request))
        );
        return;
    }

    if (url.pathname === '/manifest.json') {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetchAndCache(request))
        );
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) => cached || caches.match('/'))
                )
        );
        return;
    }

    event.respondWith(
        fetch(request)
            .then((response) => {
                const copy = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                return response;
            })
            .catch(() => caches.match(request))
    );
});

function fetchAndCache(request) {
    return fetch(request).then((response) => {
        const copy = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
        return response;
    });
}
