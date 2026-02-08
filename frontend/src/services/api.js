const API_URL = 'http://localhost:8000'

async function request(path, options = {}) {
  const response = await fetch(`${API_URL}?path=${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  })

  const data = await response.json()

  if (!response.ok) {
    const error = new Error(data.message || 'Erreur serveur')
    error.status = response.status
    error.data = data
    throw error
  }

  return data
}

function createResource(basePath) {
  return {
    list: () => request(`api/${basePath}`),
    show: (id) => request(`api/${basePath}/${id}`),
    create: (body) => request(`api/${basePath}`, { method: 'POST', body: JSON.stringify(body) }),
    update: (id, body) => request(`api/${basePath}/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
    delete: (id) => request(`api/${basePath}/${id}`, { method: 'DELETE' }),
  }
}

export const moviesApi = createResource('movies')
export const roomsApi = createResource('rooms')
export const screeningsApi = createResource('screenings')
