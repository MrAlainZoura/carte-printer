import { defineStore } from 'pinia'
import { authService } from '../services/auth.service'
import { useAppStore } from '@/stores/app.store'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: sessionStorage.getItem('carterose.jwt') || '',
    user: null,
  }),
  actions: {
    async login(credentials) {
      const result = await authService.login(credentials)
      this.token = result.access_token
      sessionStorage.setItem('carterose.jwt', this.token)
      this.user = await authService.me()
      return this.user
    },
    async loadUser() {
      if (!this.token) return null
      this.user = await authService.me()
      return this.user
    },
    async logout() {
      try {
        if (this.token) await authService.logout()
      } finally {
        this.token = ''
        this.user = null
        sessionStorage.removeItem('carterose.jwt')
        useAppStore().clearPost()
        await router.replace({ name: 'login' })
      }
    },
  },
})
