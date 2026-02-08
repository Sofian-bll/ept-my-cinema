import { ref, readonly, computed } from 'vue'
import { useApi, getErrorMessage } from './useApi'

/**
 * @typedef {Object} Screening
 * @property {number} id
 * @property {number} movies_id
 * @property {number} room_id
 * @property {string} start_time - ISO datetime
 * @property {string} end_time - ISO datetime (auto-calculated from movie duration)
 * @property {string} created_at
 * @property {string} updated_at
 * @property {Object} [movie] - Movie details when fetching single screening
 * @property {Object} [room] - Room details when fetching single screening
 */

/**
 * Screenings CRUD composable
 * @returns {Object} Screenings API methods
 */
export function useScreenings() {
  const api = useApi()
  const screenings = ref([])
  const currentScreening = ref(null)
  const loading = ref(false)
  const error = ref(null)

  /**
   * Get today's screenings
   */
  const todayScreenings = computed(() => {
    const today = new Date().toISOString().split('T')[0]
    return screenings.value.filter(s => s.start_time.startsWith(today))
  })

  /**
   * Get upcoming screenings (next 7 days)
   */
  const upcomingScreenings = computed(() => {
    const now = new Date()
    const weekFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000)
    return screenings.value.filter(s => {
      const startTime = new Date(s.start_time)
      return startTime >= now && startTime <= weekFromNow
    }).sort((a, b) => new Date(a.start_time) - new Date(b.start_time))
  })

  /**
   * Fetch all screenings
   * @returns {Promise<Screening[]>}
   */
  async function fetchAll() {
    loading.value = true
    error.value = null
    try {
      const data = await api.get('/screenings')
      screenings.value = data || []
      return screenings.value
    } catch (err) {
      error.value = getErrorMessage(err, 'screenings')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch single screening by ID
   * @param {number} id - Screening ID
   * @returns {Promise<Screening>}
   */
  async function fetchById(id) {
    loading.value = true
    error.value = null
    try {
      const data = await api.get(`/screenings/${id}`)
      currentScreening.value = data
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new screening
   * Note: end_time is auto-calculated from movie duration
   * Note: Will fail with 409 if there's a time conflict in the room
   * @param {Object} screeningData - Screening data
   * @param {number} screeningData.movies_id - Required
   * @param {number} screeningData.room_id - Required
   * @param {string} screeningData.start_time - Required (ISO datetime)
   * @returns {Promise<Screening>}
   */
  async function create(screeningData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/screenings', screeningData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Update an existing screening
   * Note: Will fail with 409 if there's a time conflict in the room
   * @param {number} id - Screening ID
   * @param {Object} screeningData - Updated screening data
   * @returns {Promise<Screening>}
   */
  async function update(id, screeningData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/screenings/${id}`, screeningData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Delete a screening
   * @param {number} id - Screening ID
   * @returns {Promise<void>}
   */
  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/screenings/${id}`)
      await fetchAll() // Refresh list
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Get screenings by date
   * @param {string} date - Date string (YYYY-MM-DD)
   * @returns {Screening[]}
   */
  function getByDate(date) {
    return screenings.value.filter(s => s.start_time.startsWith(date))
  }

  /**
   * Get screenings by room
   * @param {number} roomId - Room ID
   * @returns {Screening[]}
   */
  function getByRoom(roomId) {
    return screenings.value.filter(s => s.room_id === roomId)
  }

  /**
   * Get screenings by movie
   * @param {number} movieId - Movie ID
   * @returns {Screening[]}
   */
  function getByMovie(movieId) {
    return screenings.value.filter(s => s.movies_id === movieId)
  }

  return {
    screenings: readonly(screenings),
    currentScreening: readonly(currentScreening),
    todayScreenings,
    upcomingScreenings,
    loading: readonly(loading),
    error: readonly(error),
    fetchAll,
    fetchById,
    create,
    update,
    remove,
    getByDate,
    getByRoom,
    getByMovie
  }
}
