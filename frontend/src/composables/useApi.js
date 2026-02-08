import { ref, readonly } from 'vue'

/** @type {string} Base URL for the API server */
const API_BASE_URL = 'http://localhost:8000'

/**
 * Generic API composable for making HTTP requests
 * Uses the path query parameter routing format: /?path=/api/endpoint
 * @returns {Object} API utilities
 */
export function useApi() {
  const loading = ref(false)
  const error = ref(null)

  /**
   * Build the full URL with path query parameter
   * @param {string} endpoint - API endpoint (e.g., '/movies' or '/movies/1')
   * @returns {string} Full URL with path parameter
   */
  function buildUrl(endpoint) {
    // Ensure endpoint starts with /api
    const apiPath = endpoint.startsWith('/api') ? endpoint : `/api${endpoint}`
    return `${API_BASE_URL}/?path=${apiPath}`
  }

  /**
   * Make an HTTP request
   * @param {string} endpoint - API endpoint (e.g., '/movies' or '/movies/1')
   * @param {Object} options - Fetch options
   * @returns {Promise<any>} Response data
   */
  async function request(endpoint, options = {}) {
    loading.value = true
    error.value = null

    try {
      const url = buildUrl(endpoint)
      const config = {
        headers: {
          'Content-Type': 'application/json',
          ...options.headers
        },
        ...options
      }

      if (config.body && typeof config.body === 'object') {
        config.body = JSON.stringify(config.body)
      }

      const response = await fetch(url, config)
      
      // Handle empty responses (204 No Content)
      if (response.status === 204) {
        return null
      }

      const data = await response.json()

      if (!response.ok) {
        const errorMessage = data.error || `HTTP error ${response.status}`
        error.value = {
          message: errorMessage,
          status: response.status,
          fields: data.fields || []
        }
        throw error.value
      }

      return data
    } catch (err) {
      if (!error.value) {
        error.value = {
          message: err.message || 'Network error',
          status: 0,
          fields: []
        }
      }
      throw error.value
    } finally {
      loading.value = false
    }
  }

  /**
   * GET request
   * @param {string} endpoint - API endpoint (e.g., '/movies')
   * @returns {Promise<any>}
   */
  function get(endpoint) {
    return request(endpoint, { method: 'GET' })
  }

  /**
   * POST request
   * @param {string} endpoint - API endpoint
   * @param {Object} body - Request body
   * @returns {Promise<any>}
   */
  function post(endpoint, body) {
    return request(endpoint, { method: 'POST', body })
  }

  /**
   * PUT request
   * @param {string} endpoint - API endpoint
   * @param {Object} body - Request body
   * @returns {Promise<any>}
   */
  function put(endpoint, body) {
    return request(endpoint, { method: 'PUT', body })
  }

  /**
   * DELETE request
   * @param {string} endpoint - API endpoint
   * @returns {Promise<any>}
   */
  function del(endpoint) {
    return request(endpoint, { method: 'DELETE' })
  }

  return {
    loading: readonly(loading),
    error: readonly(error),
    get,
    post,
    put,
    del,
    request
  }
}

/**
 * Get user-friendly error message based on status code
 * @param {Object} error - Error object
 * @param {string} entity - Entity name (e.g., 'movie', 'room')
 * @returns {string} User-friendly message
 */
export function getErrorMessage(error, entity = 'item') {
  if (!error) return 'An unknown error occurred'

  switch (error.status) {
    case 400:
      return `Missing required fields: ${error.fields?.join(', ') || 'unknown'}`
    case 404:
      return `${entity.charAt(0).toUpperCase() + entity.slice(1)} not found`
    case 409:
      return error.message || `Cannot complete operation - ${entity} has dependencies`
    case 410:
      return `${entity.charAt(0).toUpperCase() + entity.slice(1)} has already been deleted`
    case 500:
      return 'Server error. Please try again later.'
    default:
      return error.message || 'An unexpected error occurred'
  }
}
