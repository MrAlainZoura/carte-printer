<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import BasePagination from '@/components/common/BasePagination.vue'
import { resources } from '../resource.registry'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const config = computed(() => resources[route.meta.resource])
const rows = ref([])
const pagination = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref(route.query.message || '')

function valueAt(row, key) {
  return key.split('.').reduce((value, part) => value?.[part], row)
}

async function load(page = 1) {
  loading.value = true
  error.value = ''
  try {
    const result = await config.value.service.list({ page })
    rows.value = result.data || []
    pagination.value = result
  } catch (requestError) {
    error.value = responseMessage(requestError, `Impossible de charger : ${config.value.title.toLowerCase()}.`)
  } finally {
    loading.value = false
  }
}

async function remove(row) {
  const name = row.libele || row.nom_complet || row.raison_sociale || row.produit?.libele || row.id
  if (!window.confirm(`Supprimer cet élément (${name}) ?`)) return
  error.value = ''
  success.value = ''
  try {
    const result = await config.value.service.remove(row.id)
    if (result.deleted === false) {
      error.value = result.message
      return
    }
    success.value = result.message
    await load(pagination.value?.current_page || 1)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de supprimer cet élément.')
  }
}

async function confirmSupply(row) {
  error.value = ''
  success.value = ''
  try {
    const result = await config.value.service.confirm(row.id)
    success.value = result.message
    await load(pagination.value?.current_page || 1)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de confirmer la réception.')
  }
}

onMounted(() => load())
</script>

<template>
  <section class="page">
    <div class="spread">
      <div><h1>{{ config.title }}</h1><p class="muted">{{ config.description }}</p></div>
      <button type="button" @click="router.push({ name: `${route.meta.resource}.create` })">Créer</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <p v-if="success" class="success" role="status">{{ success }}</p>
    <BaseLoader v-if="loading">Chargement…</BaseLoader>
    <div v-else class="card table-wrap">
      <table>
        <thead><tr><th v-for="column in config.columns" :key="column.key">{{ column.label }}</th><th>Actions</th></tr></thead>
        <tbody>
          <tr v-for="row in rows" :key="row.id">
            <td v-for="column in config.columns" :key="column.key">
              {{ column.format ? column.format(valueAt(row, column.key)) : valueAt(row, column.key) || '—' }}
            </td>
            <td class="actions">
              <button type="button" @click="router.push({ name: `${route.meta.resource}.show`, params: { id: row.id } })">Détails</button>
              <button type="button" @click="router.push({ name: `${route.meta.resource}.edit`, params: { id: row.id } })">Modifier</button>
              <button v-if="route.meta.resource === 'supplies' && !row.confirm" type="button" @click="confirmSupply(row)">Confirmer</button>
              <button type="button" @click="remove(row)">Supprimer</button>
            </td>
          </tr>
          <tr v-if="rows.length === 0"><td :colspan="config.columns.length + 1" class="muted empty">Aucun élément.</td></tr>
        </tbody>
      </table>
      <BasePagination
        v-if="pagination"
        :current-page="pagination.current_page"
        :last-page="pagination.last_page"
        @change="load"
      />
    </div>
  </section>
</template>

<style scoped>
.actions { display: flex; gap: 0.5rem; }
button { padding: 0.45rem 0.7rem; }
.empty { text-align: center; }
</style>
