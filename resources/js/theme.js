const html = document.documentElement;
const toggle = document.querySelector('#toggle-theme');

const saved = localStorage.getItem('theme');
if (saved) html.className = saved;

toggle?.addEventListener('click', () => {
    const isDark = html.classList.contains('theme-dark');
    html.className = isDark ? 'theme-light' : 'theme-dark';
    localStorage.setItem('theme', html.className);
});
