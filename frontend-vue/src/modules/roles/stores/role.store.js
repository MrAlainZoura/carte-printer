import { defineStore } from 'pinia'
import { roleService } from '../services/role.service'

export const useRoleStore = defineStore('roles', {
  state: () => ({ roles: [], loading: false }),
  actions: {
    async fetch() {
      this.loading = true
      try {
        this.roles = await roleService.list()
      } finally {
        this.loading = false
      }
    },
    async remove(id) {
      const result = await roleService.remove(id)
      await this.fetch()
      return result
    },
  },
})
