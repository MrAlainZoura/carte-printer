import api from '@/services/api'

export const postService = {
  async options() {
    const { data } = await api.get('/postes')
    return data.data
  },
  async select(postId) {
    const { data } = await api.post('/postes/select', { post_travail_id: postId })
    return data
  },
  async selectGlobal() {
    const { data } = await api.post('/postes/global')
    return data
  },
  async openPost(postId) {
    const { data } = await api.post(`/postes/${postId}/open`)
    return data
  },
  async createInitial(payload) {
    const { data } = await api.post('/initialisation/poste', payload)
    return data
  },
}
