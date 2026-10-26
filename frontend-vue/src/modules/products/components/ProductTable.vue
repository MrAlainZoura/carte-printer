<script setup>
import { useRouter } from 'vue-router'

defineProps({ products: { type: Array, default: () => [] } })
const emit = defineEmits(['delete'])
const router = useRouter()
</script>

<template>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Libellé</th><th>Catégorie</th><th>Marque</th><th>Unité</th><th>Actions</th></tr></thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.libele }}</td>
          <td>{{ product.categorie || '—' }}</td>
          <td>{{ product.marque || '—' }}</td>
          <td>{{ product.unite || '—' }}</td>
          <td class="actions">
            <button type="button" @click="router.push({ name: 'products.show', params: { id: product.id } })">Détails</button>
            <button type="button" @click="router.push({ name: 'products.edit', params: { id: product.id } })">Modifier</button>
            <button type="button" @click="emit('delete', product)">Supprimer</button>
          </td>
        </tr>
        <tr v-if="products.length === 0"><td colspan="5" class="muted">Aucun produit.</td></tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.actions { display: flex; gap: 0.5rem; }
button { padding: 0.4rem 0.65rem; }
</style>
