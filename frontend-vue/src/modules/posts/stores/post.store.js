import { defineStore } from 'pinia'
import { postService } from '../services/post.service'

export const usePostStore = defineStore('posts', {
  state: () => ({
    posts: [],
    isAdmin: false,
    needsInitialSetup: false,
    loading: false,
    currentPostId: sessionStorage.getItem('carterose.postId') || '',
    globalView: sessionStorage.getItem('carterose.global') === 'true',
  }),
  getters: {
    currentPost: (state) => state.posts.find((post) => String(post.id) === state.currentPostId) || null,
  },
  actions: {
    async fetchOptions() {
      this.loading = true
      try {
        const options = await postService.options()
        this.posts = options.posts
        this.isAdmin = options.isAdmin
        this.needsInitialSetup = options.needsInitialSetup

        if (this.currentPostId && !this.posts.some((post) => String(post.id) === this.currentPostId)) {
          this.clearSelection()
        }

        if (this.globalView && !this.isAdmin) this.clearSelection()

        if (!this.currentPostId && !this.globalView && !this.isAdmin) {
          const defaultPost = this.posts.find((post) => post.pivot?.is_default)
          if (defaultPost) this.setPost(defaultPost.id)
        }

        return options
      } finally {
        this.loading = false
      }
    },
    async selectPost(postId) {
      await postService.select(postId)
      this.setPost(postId)
    },
    async selectGlobal() {
      await postService.selectGlobal()
      this.currentPostId = ''
      this.globalView = true
      sessionStorage.removeItem('carterose.postId')
      sessionStorage.setItem('carterose.global', 'true')
    },
    async openPost(postId) {
      await postService.openPost(postId)
      this.setPost(postId)
    },
    async createInitialPost(payload) {
      const result = await postService.createInitial(payload)
      this.setPost(result.data.id)
    },
    setPost(postId) {
      this.currentPostId = String(postId)
      this.globalView = false
      sessionStorage.setItem('carterose.postId', this.currentPostId)
      sessionStorage.removeItem('carterose.global')
    },
    clearSelection() {
      this.currentPostId = ''
      this.globalView = false
      sessionStorage.removeItem('carterose.postId')
      sessionStorage.removeItem('carterose.global')
    },
  },
})
