import { ref, readonly } from 'vue'

const STORAGE_KEY = 'my-cinema-theme'

function getInitialTheme() {
  if (typeof window === 'undefined') return 'dark'
  
  const stored = localStorage.getItem(STORAGE_KEY)
  if (stored === 'light' || stored === 'dark' || stored === 'system') {
    return stored
  }
  
  return 'dark'
}

function systemPrefersDark() {
  if (typeof window === 'undefined') return true
  return window.matchMedia('(prefers-color-scheme: dark)').matches
}

const theme = ref(getInitialTheme())
const isDark = ref(true)

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

export function useTheme() {
  function setTheme(value) {
    theme.value = value
    localStorage.setItem(STORAGE_KEY, value)
    applyTheme(value)
  }

  function toggleTheme() {
    const newTheme = isDark.value ? 'light' : 'dark'
    setTheme(newTheme)
  }

  function initTheme() {
    applyTheme(theme.value)
    
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
