<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import ResourceForm from '../components/ResourceForm.vue'
import { resources } from '../resource.registry'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const config = computed(() => resources[route.meta.resource])
const editing = computed(() => Boolean(route.params.id))
const formData = ref(null)
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  try {
    formData.value = await config.value.service.formData(route.params.id)
    if (config.value.normalizeForm) formData.value = config.value.normalizeForm(formData.value)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le formulaire.')
  }
})

async function submit(payload) {
  loading.value = true
  error.value = ''
  try {
    const result = editing.value
      ? await config.value.service.update(route.params.id, payload)
      : await config.value.service.create(payload)
    await router.push({ name: `${route.meta.resource}.index`, query: { message: result.message } })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible d’enregistrer les modifications.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>{{ editing ? 'Modifier' : 'Créer' }} — {{ config.title }}</h1>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!formData && !error">Chargement du formulaire…</BaseLoader>
    <div v-else-if="formData" class="card">
      <ResourceForm
        :fields="config.fields"
        :initial-values="formData.record"
        :options="formData"
        :loading="loading"
        @submit="submit"
      />
    </div>
  </section>
</template>
