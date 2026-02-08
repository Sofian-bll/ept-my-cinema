import { clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

/**
 * Combines clsx and tailwind-merge for conditional class names
 * @param  {...any} inputs - Class names or conditional objects
 * @returns {string} Merged class string
 */
export function cn(...inputs) {
  return twMerge(clsx(inputs))
}

/**
 * Helper for TanStack Table state updates
 * @param {Function|any} updaterOrValue - Either a function or a value
 * @param {import('vue').Ref} ref - Vue ref to update
 */
export function valueUpdater(updaterOrValue, ref) {
  ref.value = typeof updaterOrValue === 'function'
    ? updaterOrValue(ref.value)
    : updaterOrValue
}

/**
 * Format duration in minutes to human readable format
 * @param {number} minutes - Duration in minutes
 * @returns {string} Formatted duration (e.g., "2h 22min")
 */
export function formatDuration(minutes) {
  if (!minutes) return '-'
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  if (hours === 0) return `${mins}min`
  if (mins === 0) return `${hours}h`
  return `${hours}h ${mins}min`
}

/**
 * Format date to locale string
 * @param {string} dateString - ISO date string
 * @param {object} options - Intl.DateTimeFormat options
 * @returns {string} Formatted date
 */
export function formatDate(dateString, options = {}) {
  if (!dateString) return '-'
  const defaultOptions = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    ...options
  }
  return new Date(dateString).toLocaleDateString('en-US', defaultOptions)
}

/**
 * Format datetime to locale string
 * @param {string} dateString - ISO datetime string
 * @returns {string} Formatted datetime
 */
export function formatDateTime(dateString) {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

/**
 * Format time only from datetime string
 * @param {string} dateString - ISO datetime string
 * @returns {string} Formatted time (e.g., "14:30")
 */
export function formatTime(dateString) {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  })
}

/**
 * Get room type badge variant
 * @param {string} type - Room type
 * @returns {string} Badge variant
 */
export function getRoomTypeVariant(type) {
  const variants = {
    'Standard': 'secondary',
    'Premium': 'default',
    'IMAX': 'destructive',
    'VIP': 'default',
    '4DX': 'destructive',
    'Dolby Atmos': 'default'
  }
  return variants[type] || 'secondary'
}

/**
 * Debounce function
 * @param {Function} fn - Function to debounce
 * @param {number} delay - Delay in milliseconds
 * @returns {Function} Debounced function
 */
export function debounce(fn, delay = 300) {
  let timeoutId
  return (...args) => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => fn(...args), delay)
  }
}
