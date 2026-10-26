import api from './api'

export function createResourceService({ endpoint, listKey }) {
  return {
    async list(params = {}) {
      const { data } = await api.get(endpoint, { params })
      return data.data[listKey]
    },
    async formData(id) {
      const { data } = await api.get(id ? `${endpoint}/${id}/edit` : `${endpoint}/create`)
      return data.data
    },
    async show(id) {
      const { data } = await api.get(`${endpoint}/${id}`)
      return data.data
    },
    async create(payload) {
      const { data } = await api.post(endpoint, payload)
      return data
    },
    async update(id, payload) {
      const { data } = await api.put(`${endpoint}/${id}`, payload)
      return data
    },
    async remove(id) {
      const { data } = await api.delete(`${endpoint}/${id}`)
      return data
    },
    async confirm(id) {
      const { data } = await api.patch(`${endpoint}/${id}/confirm`)
      return data
    },
  }
}
