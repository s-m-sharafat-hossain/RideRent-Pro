/**
 * Theme Management JavaScript
 * 
 * This file handles theme switching functionality for the RideRentPro application.
 * It manages light/dark theme preferences using localStorage and updates the UI accordingly.
 * 
 * @package RideRentPro\Assets\JS
 * @author RideRent Pro Team
 * @version 1.0.0
 */

/**
 * Initialize the theme system
 * 
 * Loads the saved theme from localStorage or defaults to 'light',
 * applies it to the document body, and updates the UI elements.
 * 
 * @return void
 */
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (!document.body.hasAttribute('data-theme')) {
        document.body.setAttribute('data-theme', savedTheme);
    }
    updateThemeUI(savedTheme);
}

// Initialize theme when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
} else {
    initTheme();
}

/**
 * Toggle between light and dark themes
 * 
 * Switches the current theme to the opposite theme, saves the preference
 * to localStorage, and updates the UI elements accordingly.
 * 
 * @return void
 */
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeUI(newTheme);
}

/**
 * Update theme UI elements
 * 
 * Updates the visual appearance of theme toggle buttons and icons
 * based on the current theme.
 * 
 * @param {string} theme - The current theme ('light' or 'dark')
 * @return void
 */
function updateThemeUI(theme) {
    const icons = document.querySelectorAll('.theme-toggle i');
    const texts = document.querySelectorAll('.theme-toggle span');
    
    icons.forEach(icon => {
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    });
    
    texts.forEach(text => {
        if (theme === 'dark') {
            text.textContent = 'Light';
        } else {
            text.textContent = 'Dark';
        }
    });
}
