import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use((config) => {
  const token = sessionStorage.getItem('carterose.jwt')
  const postId = sessionStorage.getItem('carterose.postId')
  const global = sessionStorage.getItem('carterose.global')

  if (token) config.headers.Authorization = `Bearer ${token}`
  if (postId) config.headers['X-Post-Travail-Id'] = postId
  if (global === 'true') config.headers['X-Post-Travail-Global'] = 'true'

  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      sessionStorage.removeItem('carterose.jwt')
      sessionStorage.removeItem('carterose.postId')
      sessionStorage.removeItem('carterose.global')
      if (window.location.pathname !== '/login') window.location.assign('/login')
    }

    return Promise.reject(error)
  },
)

export function responseMessage(error, fallback = 'Une erreur est survenue.') {
  const errors = error.response?.data?.errors
  if (errors) return Object.values(errors).flat().join(' ')
  return error.response?.data?.message || error.response?.data?.error || fallback
}

export default api
