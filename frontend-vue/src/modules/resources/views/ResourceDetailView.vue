<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import { resources } from '../resource.registry'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const config = computed(() => resources[route.meta.resource])
const record = ref(null)
const error = ref('')

function valueAt(key) {
  return key.split('.').reduce((value, part) => value?.[part], record.value)
}

onMounted(async () => {
  try {
    const result = await config.value.service.show(route.params.id)
    record.value = result[config.value.showKey]
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger cet élément.')
  }
})
</script>

<template>
  <section class="page">
    <div class="spread">
      <h1>Détail — {{ config.title }}</h1>
      <button type="button" @click="router.push({ name: `${route.meta.resource}.edit`, params: { id: route.params.id } })">Modifier</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!record && !error">Chargement…</BaseLoader>
    <dl v-else-if="record" class="card details">
      <template v-for="column in config.columns" :key="column.key">
        <dt>{{ column.label }}</dt>
        <dd>{{ valueAt(column.key) ?? '—' }}</dd>
      </template>
      <template v-for="field in config.fields" :key="field.key">
        <template v-if="!config.columns.some((column) => column.key === field.key)">
          <dt>{{ field.label }}</dt>
          <dd>{{ Array.isArray(record[field.key]) ? record[field.key].join(', ') || '—' : record[field.key] ?? '—' }}</dd>
        </template>
      </template>
    </dl>
  </section>
</template>

<style scoped>
.details { display: grid; grid-template-columns: minmax(150px, 1fr) 2fr; gap: 0.7rem 1rem; }
.details dt { color: var(--color-muted); }
.details dd { margin: 0; white-space: pre-wrap; }
</style>
