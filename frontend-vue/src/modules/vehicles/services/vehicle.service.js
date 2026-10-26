import api from '@/services/api'

export const vehicleService = {
  async list(params = {}) {
    const { data } = await api.get('/vehicules', { params })
    return data.data
  },
  async formData(id) {
    const { data } = await api.get(id ? `/vehicules/${id}/edit` : '/vehicules/create')
    return data.data
  },
  async show(id) {
    const { data } = await api.get(`/vehicules/${id}`)
    return data.data
  },
  async create(payload) {
    const { data } = await api.post('/vehicules', payload)
    return data
  },
  async update(id, payload) {
    const { data } = await api.put(`/vehicules/${id}`, payload)
    return data
  },
  async remove(id) {
    const { data } = await api.delete(`/vehicules/${id}`)
    return data
  },
  async ownerOptions(id) {
    const { data } = await api.get(`/vehicules/${id}/proprietaire`)
    return data.data
  },
  async transferOwner(id, propietaireId) {
    const { data } = await api.post(`/vehicules/${id}/proprietaire`, { propietaire_id: propietaireId })
    return data
  },
  async ownerHistory(id, params = {}) {
    const { data } = await api.get(`/vehicules/${id}/historique-proprietaires`, { params })
    return data.data
  },
  async changePlate(id, payload) {
    const { data } = await api.post(`/vehicules/${id}/plaque`, payload)
    return data
  },
  async changePlateForm(id) {
    const { data } = await api.get(`/vehicules/${id}/plaque`)
    return data.data
  },
  async plateForm(vehicleId, plateId) {
    const { data } = await api.get(`/vehicules/${vehicleId}/plaque/${plateId}/edit`)
    return data.data
  },
  async updatePlate(vehicleId, plateId, payload) {
    const { data } = await api.put(`/vehicules/${vehicleId}/plaque/${plateId}`, payload)
    return data
  },
  async updateCard(vehicleId, cardId, payload) {
    const { data } = await api.put(`/vehicules/${vehicleId}/carte-rose/${cardId}`, payload)
    return data
  },
  async cardForm(vehicleId, cardId) {
    const { data } = await api.get(`/vehicules/${vehicleId}/carte-rose/${cardId}/edit`)
    return data.data
  },
  async printCard(vehicleId) {
    const { data } = await api.get(`/carte/${vehicleId}`, { responseType: 'blob' })
    return data
  },
}
