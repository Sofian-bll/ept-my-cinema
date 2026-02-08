import { ref, watch, readonly } from 'vue'

/**
 * Theme options
 * @typedef {'light' | 'dark' | 'system'} Theme
 */

const STORAGE_KEY = 'my-cinema-theme'

/**
 * Get initial theme from localStorage or system preference
 * @returns {Theme}
 */
function getInitialTheme() {
  if (typeof window === 'undefined') return 'dark'
  
  const stored = localStorage.getItem(STORAGE_KEY)
  if (stored === 'light' || stored === 'dark' || stored === 'system') {
    return stored
  }
  
  // Default to dark for cinema theme
  return 'dark'
}

/**
 * Check if system prefers dark mode
 * @returns {boolean}
 */
function systemPrefersDark() {
  if (typeof window === 'undefined') return true
  return window.matchMedia('(prefers-color-scheme: dark)').matches
}

/** @type {import('vue').Ref<Theme>} */
const theme = ref(getInitialTheme())

/** @type {import('vue').Ref<boolean>} */
const isDark = ref(true)

/**
 * Apply theme to document
 * @param {Theme} value
 */
function applyTheme(value) {
  const root = document.documentElement
  
  if (value === 'system') {
    isDark.value = systemPrefersDark()
  } else {
    isDark.value = value === 'dark'
  }
  
  root.classList.remove('light', 'dark')
  root.classList.add(isDark.value ? 'dark' : 'light')
}

/**
 * Theme composable for dark/light mode toggle
 * @returns {Object} Theme utilities
 */
export function useTheme() {
  /**
   * Set theme
   * @param {Theme} value
   */
  function setTheme(value) {
    theme.value = value
    localStorage.setItem(STORAGE_KEY, value)
    applyTheme(value)
  }

  /**
   * Toggle between light and dark
   */
  function toggleTheme() {
    const newTheme = isDark.value ? 'light' : 'dark'
    setTheme(newTheme)
  }

  /**
   * Initialize theme on mount
   */
  function initTheme() {
    applyTheme(theme.value)
    
    // Listen for system preference changes
    if (typeof window !== 'undefined') {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (theme.value === 'system') {
          applyTheme('system')
        }
      })
    }
  }

  return {
    theme: readonly(theme),
    isDark: readonly(isDark),
    setTheme,
    toggleTheme,
    initTheme
  }
}
