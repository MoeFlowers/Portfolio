
const themeToggle = document.getElementById('theme-toggle');
const darkIcon = document.getElementById('theme-toggle-dark-icon');
const lightIcon = document.getElementById('theme-toggle-light-icon');

// Verifica el tema actual
const isDarkMode = localStorage.getItem('theme') === 'dark';
if (isDarkMode) {
    document.documentElement.classList.add('dark');
    darkIcon.classList.remove('hidden');
} else {
    lightIcon.classList.remove('hidden');
}

themeToggle.addEventListener('click', () => {
    darkIcon.classList.toggle('hidden');
    lightIcon.classList.toggle('hidden');
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
});
