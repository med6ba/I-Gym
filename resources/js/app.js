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

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    });
}
