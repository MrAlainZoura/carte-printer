import api from '@/services/api'

export const userService = {
  async list(params = {}) {
    const { data } = await api.get('/users', { params })
    return data.data.users
  },
  async show(id) {
    const { data } = await api.get(`/users/${id}`)
    return data.data
  },
  async formData(id) {
    const { data } = await api.get(id ? `/users/${id}/edit` : '/users/create')
    return data.data
  },
  async create(payload) {
    const { data } = await api.post('/users', payload)
    return data
  },
  async update(id, payload) {
    const { data } = await api.put(`/users/${id}`, payload)
    return data
  },
  async remove(id) {
    const { data } = await api.delete(`/users/${id}`)
    return data
  },
}
