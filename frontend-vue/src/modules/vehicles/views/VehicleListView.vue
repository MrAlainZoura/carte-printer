<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import VehicleTable from '../components/VehicleTable.vue'
import { useVehicleStore } from '../stores/vehicle.store'
import { vehicleService } from '../services/vehicle.service'
import { responseMessage } from '@/services/api'

const router = useRouter()
const route = useRoute()
const store = useVehicleStore()
const error = ref('')
const success = ref(route.query.message || '')

async function load(page = 1) {
  error.value = ''
  try {
    await store.fetch({ page })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les véhicules.')
  }
}

async function remove(vehicle) {
  if (!window.confirm(`Supprimer le véhicule ${vehicle.immatriculation || `#${vehicle.id}`} ?`)) return
  error.value = ''
  success.value = ''
  try {
    const result = await vehicleService.remove(vehicle.id)
    await load(store.pagination?.current_page || 1)
    success.value = result.message
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de supprimer ce véhicule.')
  }
}

onMounted(() => load())
</script>

<template>
  <section class="page">
    <div class="spread">
      <div><h1>Véhicules</h1><p class="muted">Véhicules enregistrés au poste sélectionné.</p></div>
      <button type="button" @click="router.push({ name: 'vehicles.create' })">Ajouter un véhicule</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <p v-if="success" role="status">{{ success }}</p>
    <BaseLoader v-if="store.loading">Chargement des véhicules…</BaseLoader>
    <div v-else class="card">
      <VehicleTable
        :vehicles="store.vehicles"
        :pagination="store.pagination"
        @page="load"
        @show="router.push(`/vehicles/${$event}`)"
        @edit="router.push(`/vehicles/${$event}/edit`)"
        @delete="remove"
      />
    </div>
  </section>
</template>
