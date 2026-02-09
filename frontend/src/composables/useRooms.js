import { ref, readonly } from 'vue'
import { useApi, getErrorMessage } from './useApi'

export function useRooms() {
  const api = useApi()
  const rooms = ref([])
  const currentRoom = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const roomTypes = [
    'Standard',
    'Premium',
    'IMAX',
    'VIP',
    '4DX',
    'Dolby Atmos'
  ]

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

  async function create(roomData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/rooms', roomData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function update(id, roomData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/rooms/${id}`, roomData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'room')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/rooms/${id}`)
      await fetchAll()
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
