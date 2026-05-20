import './bootstrap';
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';

window.Chart = Chart;
window.Alpine = Alpine;

Alpine.start();

const preferredTheme = document.documentElement.dataset.authenticated === '1'
    ? (document.documentElement.dataset.userTheme || 'light')
    : (localStorage.getItem('igym-theme') || 'light');

function applyTheme(theme) {
    const resolvedTheme = theme === 'system'
        ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
        : theme;

    document.documentElement.classList.toggle('dark', resolvedTheme === 'dark');
    localStorage.setItem('igym-theme', theme);
    window.dispatchEvent(new CustomEvent('igym-theme-changed', { detail: theme }));
    window.dispatchEvent(new CustomEvent('igym-theme-applied', { detail: resolvedTheme }));
}

applyTheme(preferredTheme);

window.igymSetTheme = (theme) => {
    applyTheme(theme);

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        fetch('/settings/theme', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ theme }),
        }).catch(() => {});
    }
};

window.igymCharts = window.igymCharts || {};

window.igymChart = (id, config) => {
    const element = document.getElementById(id);
    if (!element || !window.Chart) {
        return null;
    }

    if (window.igymCharts[id]) {
        window.igymCharts[id].destroy();
    }

    element.style.display = 'block';
    element.style.width = '100%';
    element.style.height = '100%';

    const dark = document.documentElement.classList.contains('dark');
    const grid = dark ? 'rgba(148, 163, 184, .18)' : 'rgba(100, 116, 139, .18)';
    const text = dark ? '#CBD5E1' : '#475569';

    config.options = {
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 120,
        ...config.options,
        plugins: {
            ...(config.options?.plugins || {}),
            legend: {
                labels: { color: text, boxWidth: 12, boxHeight: 12 },
                ...(config.options?.plugins?.legend || {}),
            },
        },
        scales: config.options?.scales || (['line', 'bar'].includes(config.type) ? {
            x: { grid: { color: grid }, ticks: { color: text } },
            y: { grid: { color: grid }, ticks: { color: text }, beginAtZero: true },
        } : undefined),
    };

    window.igymCharts[id] = new Chart(element, config);

    return window.igymCharts[id];
};

window.addEventListener('igym-theme-applied', () => {
    Object.values(window.igymCharts || {}).forEach((chart) => chart.update());
});

function playNotificationTone() {
    const AudioContext = window.AudioContext || window.webkitAudioContext;

    if (!AudioContext) {
        return;
    }

    const context = new AudioContext();
    const oscillator = context.createOscillator();
    const gain = context.createGain();

    oscillator.type = 'sine';
    oscillator.frequency.setValueAtTime(880, context.currentTime);
    gain.gain.setValueAtTime(0.0001, context.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.08, context.currentTime + 0.02);
    gain.gain.exponentialRampToValueAtTime(0.0001, context.currentTime + 0.18);
    oscillator.connect(gain);
    gain.connect(context.destination);
    oscillator.start();
    oscillator.stop(context.currentTime + 0.2);
}

window.addEventListener('load', () => {
    const count = Number(document.body.dataset.notificationCount || 0);
    const previous = Number(localStorage.getItem('igym-notification-count') || 0);

    if (count > previous && previous > 0) {
        playNotificationTone();
    }

    localStorage.setItem('igym-notification-count', String(count));
});


let deferredPrompt;
let serviceWorkerRegistration;
let refreshing = false;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.body.dataset.pwaInstallable = '1';
});

window.installPwa = function () {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(() => {
        deferredPrompt = null;
        delete document.body.dataset.pwaInstallable;
    });
};

const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
const isStandalone = window.matchMedia('(display-mode: standalone)').matches;

if (isIos && !isStandalone) {
    document.body.dataset.iosInstallable = '1';
}

window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    delete document.body.dataset.pwaInstallable;
    delete document.body.dataset.iosInstallable;
});

window.addEventListener('online', () => document.body.dataset.isOnline = '1');
window.addEventListener('offline', () => delete document.body.dataset.isOnline);

if (navigator.onLine) {
    document.body.dataset.isOnline = '1';
}

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then((reg) => {
        serviceWorkerRegistration = reg;

        if (reg.waiting) {
            document.body.dataset.swWaiting = '1';
        }

        reg.addEventListener('updatefound', () => {
            const worker = reg.installing;
            if (!worker) return;

            worker.addEventListener('statechange', () => {
                if (worker.state === 'installed' && navigator.serviceWorker.controller) {
                    document.body.dataset.swWaiting = '1';
                }
            });
        });
    }).catch(() => {});

    navigator.serviceWorker.addEventListener('controllerchange', () => {
        if (refreshing) return;
        refreshing = true;
        window.location.reload();
    });

    navigator.serviceWorker.addEventListener('message', (event) => {
        if (event.data?.type === 'SW_UPDATED') {
            document.body.dataset.swWaiting = '1';
        }
    });
}

window.installSwUpdate = function () {
    const waitingWorker = serviceWorkerRegistration?.waiting;

    if (waitingWorker) {
        waitingWorker.postMessage({ type: 'SKIP_WAITING' });
    }
};
