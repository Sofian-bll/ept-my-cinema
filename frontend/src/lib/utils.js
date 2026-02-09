import { clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs) {
  return twMerge(clsx(inputs))
}

export function valueUpdater(updaterOrValue, ref) {
  ref.value = typeof updaterOrValue === 'function'
    ? updaterOrValue(ref.value)
    : updaterOrValue
}

export function formatDuration(minutes) {
  if (!minutes) return '-'
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  if (hours === 0) return `${mins}min`
  if (mins === 0) return `${hours}h`
  return `${hours}h ${mins}min`
}

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

export function formatTime(dateString) {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  })
}

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

export function debounce(fn, delay = 300) {
  let timeoutId
  return (...args) => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => fn(...args), delay)
  }
}
