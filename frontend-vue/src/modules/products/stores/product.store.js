import { defineStore } from 'pinia'
import { productService } from '../services/product.service'

export const useProductStore = defineStore('products', {
  state: () => ({ products: [], categories: [], pagination: null, loading: false }),
  actions: {
    async fetch(params = {}) {
      this.loading = true
      try {
        const result = await productService.list(params)
        this.pagination = result.produits
        this.products = this.pagination.data || []
        this.categories = result.categories || []
      } finally {
        this.loading = false
      }
    },
  },
})
