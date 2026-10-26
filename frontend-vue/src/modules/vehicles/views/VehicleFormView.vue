<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import VehicleForm from '../components/VehicleForm.vue'
import { vehicleService } from '../services/vehicle.service'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const initialValues = ref(null)
const loading = ref(false)
const error = ref('')
const editing = Boolean(route.params.id)

onMounted(async () => {
  try {
    initialValues.value = await vehicleService.formData(route.params.id)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le formulaire du véhicule.')
  }
})

async function submit(payload) {
  loading.value = true
  error.value = ''
  try {
    const result = editing
      ? await vehicleService.update(route.params.id, payload)
      : await vehicleService.create(payload)
    await router.push({ name: 'vehicles.index', query: { message: result.message } })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible d’enregistrer le véhicule.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>{{ editing ? 'Modifier le véhicule' : 'Enregistrer un véhicule' }}</h1>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!initialValues && !error">Chargement du formulaire…</BaseLoader>
    <div v-else-if="initialValues" class="card">
      <VehicleForm :initial-values="initialValues" :editing="editing" :loading="loading" @submit="submit" />
    </div>
  </section>
</template>
