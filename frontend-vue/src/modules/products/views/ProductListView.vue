<script setup>
import { onMounted, reactive, ref } from 'vue'
import BaseLoader from '@/components/common/BaseLoader.vue'
import BasePagination from '@/components/common/BasePagination.vue'
import ProductTable from '../components/ProductTable.vue'
import { responseMessage } from '@/services/api'
import { useProductStore } from '../stores/product.store'
import { useRouter } from 'vue-router'
import { productService } from '../services/product.service'

const router = useRouter()
const store = useProductStore()
const error = ref('')
const filters = reactive({ search: '', categorie: '' })
const success = ref('')

async function loadProducts(page = 1) {
  error.value = ''
  try {
    await store.fetch({ ...filters, page })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les produits.')
  }
}

async function deleteProduct(product) {
  if (!window.confirm(`Supprimer le produit « ${product.libele} » ?`)) return
  error.value = ''
  success.value = ''
  try {
    const result = await productService.remove(product.id)
    success.value = result.message
    await loadProducts(store.pagination?.current_page || 1)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de supprimer ce produit.')
  }
}

onMounted(() => loadProducts())
</script>

<template>
  <section class="page">
    <div class="spread">
      <div><h1>Produits</h1><p class="muted">Catalogue des produits et fournitures.</p></div>
      <button type="button" @click="router.push({ name: 'products.create' })">Créer un produit</button>
    </div>
    <p v-if="error" class="alert">{{ error }}</p>
    <p v-if="success" class="success" role="status">{{ success }}</p>
    <BaseLoader v-else-if="store.loading">Chargement des produits…</BaseLoader>
    <template v-else>
      <form class="product-filters" @submit.prevent="loadProducts()">
        <label>
          <span>Rechercher</span>
          <input v-model.trim="filters.search" type="search" placeholder="Libellé, marque…" />
        </label>
        <label>
          <span>Catégorie</span>
          <select v-model="filters.categorie" @change="loadProducts()">
            <option value="">Toutes les catégories</option>
            <option v-for="category in store.categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </label>
        <button type="submit">Filtrer</button>
      </form>
      <div class="card">
        <ProductTable :products="store.products" @delete="deleteProduct" />
        <BasePagination
          v-if="store.pagination"
          :current-page="store.pagination.current_page"
          :last-page="store.pagination.last_page"
          @change="loadProducts"
        />
      </div>
    </template>
  </section>
</template>

<style scoped>
.product-filters {
  display: flex;
  align-items: end;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

label {
  display: grid;
  gap: 0.35rem;
}

input,
select,
button {
  min-height: 2.6rem;
  padding: 0.5rem 0.7rem;
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
  background: white;
}

button {
  cursor: pointer;
}
</style>
