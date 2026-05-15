/**
 * Artilia — Service Worker v2
 * Strategy:
 *   • Static assets (CSS/JS/images/fonts) → Cache-First
 *   • HTML pages                           → Network-First → offline fallback
 *   • API / non-GET                        → Network-Only (pass-through)
 */

const CACHE_VERSION = 'artilia-v2';
const OFFLINE_URL   = '/offline.html';

// Files to pre-cache on install
const PRECACHE_ASSETS = [
  OFFLINE_URL,
  '/artilia.png',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
];

// ── Install: pre-cache shell assets ──────────────────────────────────────────
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION).then((cache) => cache.addAll(PRECACHE_ASSETS))
  );
  self.skipWaiting();
});

// ── Activate: remove old caches ───────────────────────────────────────────────
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_VERSION)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

// ── Fetch: routing logic ──────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Only handle same-origin requests
  if (url.origin !== self.location.origin) return;

  // Skip non-GET requests (POST, PATCH, DELETE, etc.)
  if (request.method !== 'GET') return;

  // Skip browser-sync / livereload / hot-module stuff
  if (url.pathname.startsWith('/__')) return;

  // Determine strategy by request type
  const isStaticAsset = /\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|ico|webp)(\?.*)?$/.test(url.pathname);
  const isHtmlPage    = request.headers.get('accept')?.includes('text/html');

  if (isStaticAsset) {
    // Cache-First: serve from cache, update in background
    event.respondWith(cacheFirst(request));
  } else if (isHtmlPage) {
    // Network-First: try network, fall back to cache, then offline page
    event.respondWith(networkFirst(request));
  }
  // Everything else (API JSON, etc.) → browser handles normally (no SW interception)
});

// ── Cache-First Strategy ──────────────────────────────────────────────────────
async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_VERSION);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    // Asset unavailable offline — nothing useful to return
    return new Response('', { status: 408, statusText: 'Offline' });
  }
}

// ── Network-First Strategy ────────────────────────────────────────────────────
async function networkFirst(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_VERSION);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    // Network failed — try cache
    const cached = await caches.match(request);
    if (cached) return cached;

    // No cache — show offline page
    const offline = await caches.match(OFFLINE_URL);
    return offline || new Response('<h1>Offline</h1>', {
      headers: { 'Content-Type': 'text/html' },
    });
  }
}
