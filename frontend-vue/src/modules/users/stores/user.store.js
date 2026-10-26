import { defineStore } from 'pinia'
import { userService } from '../services/user.service'

export const useUserStore = defineStore('users', {
  state: () => ({ users: [], pagination: null, loading: false }),
  actions: {
    async fetch(params = {}) {
      this.loading = true
      try {
        const result = await userService.list(params)
        this.users = result.data || []
        this.pagination = result
      } finally {
        this.loading = false
      }
    },
  },
})
