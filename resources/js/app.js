import './bootstrap';

const themeToggle = document.getElementById('theme-toggle');

const storedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const initialTheme = storedTheme || (prefersDark ? 'dark' : 'light');

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark-mode', theme === 'dark');
    if (themeToggle) {
        themeToggle.textContent = theme === 'dark' ? 'Jasny motyw' : 'Ciemny motyw';
    }
}

applyTheme(initialTheme);

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('dark-mode') ? 'light' : 'dark';
        applyTheme(nextTheme);
        localStorage.setItem('theme', nextTheme);
    });
}

