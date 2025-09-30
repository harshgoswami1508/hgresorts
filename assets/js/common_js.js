// common/custom-script.js

// Dark Mode Toggle
function initDarkMode() {
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');

    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
    }

    if (themeToggle) themeToggle.addEventListener('click', toggleTheme);
    if (mobileThemeToggle) mobileThemeToggle.addEventListener('click', toggleTheme);
}

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

// Animate Room Cards on Scroll
function initRoomCardAnimation() {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.room-card').forEach(card => observer.observe(card));
}

// Initialize All Custom Scripts
function initCustomScripts() {
    initDarkMode();
    initMobileMenu();
    initRoomCardAnimation();
}

// Run on page load
document.addEventListener('DOMContentLoaded', initCustomScripts);

// Smooth Scroll Navigation (Optional)
function navigate(event, sectionId) {
    event.preventDefault();
    const target = document.getElementById(sectionId);
    if (target) target.scrollIntoView({ behavior: 'smooth' });

    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu && !mobileMenu.classList.contains('hidden')) mobileMenu.classList.add('hidden');
}
