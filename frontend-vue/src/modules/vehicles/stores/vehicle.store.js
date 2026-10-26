import { defineStore } from 'pinia'
import { vehicleService } from '../services/vehicle.service'

export const useVehicleStore = defineStore('vehicles', {
  state: () => ({ vehicles: [], pagination: null, loading: false }),
  actions: {
    async fetch(params = {}) {
      this.loading = true
      try {
        this.pagination = await vehicleService.list(params)
        this.vehicles = this.pagination.data || []
      } finally {
        this.loading = false
      }
    },
  },
})
