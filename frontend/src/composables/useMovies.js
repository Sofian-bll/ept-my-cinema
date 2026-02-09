import { ref, readonly } from 'vue'
import { useApi, getErrorMessage } from './useApi'

export function useMovies() {
  const api = useApi()
  const movies = ref([])
  const currentMovie = ref(null)
  const loading = ref(false)
  const error = ref(null)

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

  async function create(movieData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.post('/movies', movieData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function update(id, movieData) {
    loading.value = true
    error.value = null
    try {
      const data = await api.put(`/movies/${id}`, movieData)
      await fetchAll()
      return data
    } catch (err) {
      error.value = getErrorMessage(err, 'movie')
      throw err
    } finally {
      loading.value = false
    }
  }

  async function remove(id) {
    loading.value = true
    error.value = null
    try {
      await api.del(`/movies/${id}`)
      await fetchAll()
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
