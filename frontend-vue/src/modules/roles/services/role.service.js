import api from '@/services/api'

export const roleService = {
  async list() {
    const { data } = await api.get('/roles')
    return data.data.roles
  },
  async formData(id) {
    const { data } = await api.get(id ? `/roles/${id}/edit` : '/roles/create')
    return data.data
  },
  async show(id) {
    const { data } = await api.get(`/roles/${id}`)
    return data.data
  },
  async create(payload) {
    const { data } = await api.post('/roles', payload)
    return data
  },
  async update(id, payload) {
    const { data } = await api.put(`/roles/${id}`, payload)
    return data
  },
  async syncPermissions(id, permissions) {
    const { data } = await api.put(`/roles/${id}/permissions`, { permissions })
    return data
  },
  async remove(id) {
    const { data } = await api.delete(`/roles/${id}`)
    return data
  },
}
