const themeKey = 'theme';

function applyTheme() {
    const saved = localStorage.getItem(themeKey);
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = saved || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-bs-theme', theme);
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    localStorage.setItem(themeKey, next);
    applyTheme();
}

applyTheme();

document.addEventListener('click', (e) => {
    const target = e.target;
    if (target && target.matches('[data-toggle-theme]')) {
        toggleTheme();
    }
});
