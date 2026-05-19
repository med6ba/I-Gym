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

document.addEventListener('change', (event) => {
    const form = event.target.closest('[data-ajax-filter]');

    if (!form || !window.axios) {
        return;
    }

    event.preventDefault();

    const targetSelector = form.dataset.ajaxTarget;
    const target = document.querySelector(targetSelector);
    const url = `${form.action || window.location.pathname}?${new URLSearchParams(new FormData(form)).toString()}`;

    if (!target) {
        form.submit();
        return;
    }

    target.classList.add('opacity-60');

    window.axios.get(url, { headers: { Accept: 'text/html' } })
        .then(({ data }) => {
            const doc = new DOMParser().parseFromString(data, 'text/html');
            const replacement = doc.querySelector(targetSelector);

            if (replacement) {
                target.innerHTML = replacement.innerHTML;
                window.history.replaceState({}, '', url);
            }
        })
        .finally(() => target.classList.remove('opacity-60'));
});

document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-ajax-filter]');

    if (!form || !window.axios) {
        return;
    }

    event.preventDefault();
    form.dispatchEvent(new Event('change', { bubbles: true }));
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('[data-ajax-target] a[href]');

    if (!link || !window.axios) {
        return;
    }

    const wrapper = link.closest('[data-ajax-target]');
    const targetSelector = wrapper?.dataset.ajaxTarget;
    const target = targetSelector ? document.querySelector(targetSelector) : null;

    if (!target || !link.closest('nav[role="navigation"]')) {
        return;
    }

    event.preventDefault();
    target.classList.add('opacity-60');

    window.axios.get(link.href, { headers: { Accept: 'text/html' } })
        .then(({ data }) => {
            const doc = new DOMParser().parseFromString(data, 'text/html');
            const replacement = doc.querySelector(targetSelector);

            if (replacement) {
                target.innerHTML = replacement.innerHTML;
                window.history.replaceState({}, '', link.href);
            }
        })
        .finally(() => target.classList.remove('opacity-60'));
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

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    });
}
