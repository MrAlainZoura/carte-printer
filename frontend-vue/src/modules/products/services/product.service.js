import api from '@/services/api'

export const productService = {
  async list(params = {}) {
    const { data } = await api.get('/produits', { params })
    return data.data
  },
  async remove(id) {
    const { data } = await api.delete(`/produits/${id}`)
    return data
  },
}
