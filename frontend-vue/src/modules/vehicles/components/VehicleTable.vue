<script setup>
import BasePagination from '@/components/common/BasePagination.vue'

defineProps({
  vehicles: { type: Array, default: () => [] },
  pagination: { type: Object, default: null },
})
defineEmits(['page', 'show', 'edit', 'delete'])
</script>

<template>
  <div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Immatriculation</th><th>Marque / Type</th><th>Couleur</th><th>Propriétaire</th><th></th></tr></thead>
        <tbody>
          <tr v-for="vehicle in vehicles" :key="vehicle.id">
            <td>{{ vehicle.immatriculation || vehicle.plaque?.code || '—' }}</td>
            <td>{{ [vehicle.marque, vehicle.type].filter(Boolean).join(' / ') || '—' }}</td>
            <td>{{ vehicle.couleur || '—' }}</td>
            <td>{{ vehicle.proprietaire?.nom_complet || vehicle.proprietaire?.raison_sociale || '—' }}</td>
            <td class="actions">
              <button type="button" @click="$emit('show', vehicle.id)">Détails</button>
              <button type="button" @click="$emit('edit', vehicle.id)">Modifier</button>
              <button type="button" @click="$emit('delete', vehicle)">Supprimer</button>
            </td>
          </tr>
          <tr v-if="vehicles.length === 0"><td colspan="5" class="muted">Aucun véhicule.</td></tr>
        </tbody>
      </table>
    </div>
    <BasePagination v-if="pagination" :current-page="pagination.current_page" :last-page="pagination.last_page" @change="$emit('page', $event)" />
  </div>
</template>

<style scoped>
.actions { display: flex; gap: 0.4rem; }
button { padding: 0.4rem 0.55rem; white-space: nowrap; }
</style>
