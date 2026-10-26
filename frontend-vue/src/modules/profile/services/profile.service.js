import api from '@/services/api'

export const profileService = {
  async show() {
    const { data } = await api.get('/users/profile')
    return data.data
  },
  async updatePassword(payload) {
    const { data } = await api.put('/users/password', payload)
    return data
  },
}
