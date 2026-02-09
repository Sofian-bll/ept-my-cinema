import { ref, readonly, computed } from 'vue'
import { useApi, getErrorMessage } from './useApi'

export function useScreenings() {
  const api = useApi()
  const screenings = ref([])
  const currentScreening = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const todayScreenings = computed(() => {
    const today = new Date().toISOString().split('T')[0]
    return screenings.value.filter(s => s.start_time.startsWith(today))
  })

  const upcomingScreenings = computed(() => {
    const now = new Date()
    const weekFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000)
    return screenings.value.filter(s => {
      const startTime = new Date(s.start_time)
      return startTime >= now && startTime <= weekFromNow
    }).sort((a, b) => new Date(a.start_time) - new Date(b.start_time))
  })

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

  async function create(screeningData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/screenings', screeningData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function update(id, screeningData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/screenings/${id}`, screeningData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/screenings/${id}`)
      await fetchAll()
    } catch (err) {
      error.value = getErrorMessage(err, 'screening')
      throw err
    } finally {
      loading.value = false
    }
  }

  function getByDate(date) {
    return screenings.value.filter(s => s.start_time.startsWith(date))
  }

  function getByRoom(roomId) {
    return screenings.value.filter(s => s.room_id === roomId)
  }

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
