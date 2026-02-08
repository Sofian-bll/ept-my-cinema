import { ref, readonly } from 'vue'
import { useApi, getErrorMessage } from './useApi'

/**
 * @typedef {Object} Room
 * @property {number} id
 * @property {string} name
 * @property {number} capacity
 * @property {string|null} type - Standard, Premium, IMAX, VIP, 4DX, Dolby Atmos
 * @property {boolean} active
 * @property {string} created_at
 * @property {string} updated_at
 * @property {Array} [screenings] - Available when fetching single room
 * @property {Array} [movies] - Available when fetching single room
 */

/**
 * Rooms CRUD composable
 * @returns {Object} Rooms API methods
 */
export function useRooms() {
  const api = useApi()
  const rooms = ref([])
  const currentRoom = ref(null)
  const loading = ref(false)
  const error = ref(null)

  /** @type {string[]} Available room types */
  const roomTypes = [
    'Standard',
    'Premium',
    'IMAX',
    'VIP',
    '4DX',
    'Dolby Atmos'
  ]

  /**
   * Fetch all active rooms
   * Note: API only returns active rooms
   * @returns {Promise<Room[]>}
   */
  async function fetchAll() {
    loading.value = true
    error.value = null
    try {
      const data = await api.get('/rooms')
      rooms.value = data || []
      return rooms.value
    } catch (err) {
      error.value = getErrorMessage(err, 'rooms')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch single room by ID (includes screenings and movies)
   * @param {number} id - Room ID
   * @returns {Promise<Room>}
   */
  async function fetchById(id) {
    loading.value = true
    error.value = null
    try {
      const data = await api.get(`/rooms/${id}`)
      currentRoom.value = data
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new room
   * @param {Object} roomData - Room data
   * @param {string} roomData.name - Required
   * @param {number} roomData.capacity - Required
   * @param {string} [roomData.type] - Room type
   * @returns {Promise<Room>}
   */
  async function create(roomData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/rooms', roomData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Update an existing room
   * @param {number} id - Room ID
   * @param {Object} roomData - Updated room data
   * @returns {Promise<Room>}
   */
  async function update(id, roomData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/rooms/${id}`, roomData)
      await fetchAll() // Refresh list
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Delete a room (soft delete - sets active=false)
   * Note: Will fail with 409 if room has screenings
   * Note: Will fail with 410 if room is already inactive
   * @param {number} id - Room ID
   * @returns {Promise<void>}
   */
  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/rooms/${id}`)
      await fetchAll() // Refresh list
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    rooms: readonly(rooms),
    currentRoom: readonly(currentRoom),
    loading: readonly(loading),
    error: readonly(error),
    roomTypes,
    fetchAll,
    fetchById,
    create,
    update,
    remove
  }
}
