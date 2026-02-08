import { ref, readonly } from 'vue'
import { useApi, getErrorMessage } from './useApi'

/**
 * @typedef {Object} Movie
 * @property {number} id
 * @property {string} title
 * @property {string|null} description
 * @property {number} duration - Duration in minutes
 * @property {number} release_year
 * @property {string|null} genre
 * @property {string|null} director
 * @property {string} created_at
 * @property {string} updated_at
 * @property {Array} [screenings] - Available when fetching single movie
 * @property {Array} [rooms] - Available when fetching single movie
 */

/**
 * Movies CRUD composable
 * @returns {Object} Movies API methods
 */
export function useMovies() {
  const api = useApi()
  const movies = ref([])
  const currentMovie = ref(null)
  const loading = ref(false)
  const error = ref(null)

  /**
   * Fetch all movies
   * @returns {Promise<Movie[]>}
   */
  async function fetchAll() {
    loading.value = true
    error.value = null
    try {
      const data = await api.get('/movies')
      movies.value = data || []
      return movies.value
    } catch (err) {
      error.value = getErrorMessage(err, 'movies')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch single movie by ID (includes screenings and rooms)
   * @param {number} id - Movie ID
   * @returns {Promise<Movie>}
   */
  async function fetchById(id) {
    loading.value = true
    error.value = null
    try {
      const data = await api.get(`/movies/${id}`)
      currentMovie.value = data
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new movie
   * @param {Object} movieData - Movie data
   * @param {string} movieData.title - Required
   * @param {number} movieData.duration - Required (minutes)
   * @param {number} movieData.release_year - Required
   * @param {string} [movieData.description]
   * @param {string} [movieData.genre]
   * @param {string} [movieData.director]
   * @returns {Promise<Movie>}
   */
  async function create(movieData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/movies', movieData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Update an existing movie
   * @param {number} id - Movie ID
   * @param {Object} movieData - Updated movie data
   * @returns {Promise<Movie>}
   */
  async function update(id, movieData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/movies/${id}`, movieData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Delete a movie
   * Note: Will fail with 409 if movie has screenings
   * @param {number} id - Movie ID
   * @returns {Promise<void>}
   */
  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/movies/${id}`)
      await fetchAll() // Refresh list
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    movies: readonly(movies),
    currentMovie: readonly(currentMovie),
    loading: readonly(loading),
    error: readonly(error),
    fetchAll,
    fetchById,
    create,
    update,
    remove
  }
}
