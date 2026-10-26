import api from '@/services/api'

export const authService = {
  async registrationOptions() {
    const { data } = await api.get('/register/options')
    return data.data
  },
  async register(payload) {
    const { data } = await api.post('/register', payload)
    return data
  },
  async login(credentials) {
    const { data } = await api.post('/login', credentials)
    return data
  },
  async me() {
    const { data } = await api.get('/me')
    return data
  },
  async logout() {
    const { data } = await api.post('/logout')
    return data
  },
}
