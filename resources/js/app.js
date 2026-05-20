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

function playNotificationTone(duration = 0.5) {
    const AudioContext = window.AudioContext || window.webkitAudioContext;

    if (!AudioContext) {
        return Promise.resolve(false);
    }

    const context = new AudioContext();
    const startTone = () => {
        const first = context.createOscillator();
        const second = context.createOscillator();
        const gain = context.createGain();
        const now = context.currentTime;
        const stopAt = now + duration;

        first.type = 'sine';
        second.type = 'sine';
        first.frequency.setValueAtTime(587.33, now);
        first.frequency.exponentialRampToValueAtTime(659.25, now + 0.12);
        second.frequency.setValueAtTime(783.99, now + 0.08);
        second.frequency.exponentialRampToValueAtTime(880, now + 0.2);
        gain.gain.setValueAtTime(0.0001, now);
        gain.gain.exponentialRampToValueAtTime(0.035, now + 0.035);
        gain.gain.setValueAtTime(0.035, Math.max(now + 0.06, stopAt - 0.16));
        gain.gain.exponentialRampToValueAtTime(0.0001, stopAt);
        first.connect(gain);
        second.connect(gain);
        gain.connect(context.destination);
        first.start(now);
        second.start(now + 0.08);
        first.stop(stopAt);
        second.stop(stopAt);

        window.setTimeout(() => context.close().catch(() => {}), (duration * 1000) + 150);
        return true;
    };

    if (context.state === 'suspended') {
        return context.resume().then(startTone).catch(() => false);
    }

    return Promise.resolve(startTone());
}

window.addEventListener('load', () => {
    const count = Number(document.body.dataset.notificationCount || 0);
    const isDashboard = document.body.dataset.isDashboard === '1';

    if (isDashboard && count > 0) {
        playNotificationTone().then((played) => {
            if (played) return;

            const playAfterInteraction = () => {
                playNotificationTone();
                window.removeEventListener('pointerdown', playAfterInteraction);
                window.removeEventListener('keydown', playAfterInteraction);
            };

            window.addEventListener('pointerdown', playAfterInteraction, { once: true });
            window.addEventListener('keydown', playAfterInteraction, { once: true });
        });
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

const isIos = (/iPad|iPhone|iPod/.test(navigator.userAgent)
    || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1))
    && !window.MSStream;
const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

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
