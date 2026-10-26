import { defineStore } from 'pinia'
import { usePostStore } from '@/modules/posts/stores/post.store'

export const useAppStore = defineStore('app', {
  state: () => ({}),
  getters: {
    currentPostId: () => usePostStore().currentPostId,
    globalView: () => usePostStore().globalView,
  },
  actions: {
    async selectPost(id) {
      await usePostStore().selectPost(id)
    },
    async selectGlobal() {
      await usePostStore().selectGlobal()
    },
    clearPost() {
      usePostStore().clearSelection()
    },
  },
})
