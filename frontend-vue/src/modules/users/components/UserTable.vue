<script setup>
import BasePagination from '@/components/common/BasePagination.vue'
import { formatDate } from '@/utils/formatters'
import { useRouter } from 'vue-router'

defineProps({
  users: { type: Array, default: () => [] },
  pagination: { type: Object, default: null },
})
defineEmits(['page', 'edit'])
const router = useRouter()
</script>

<template>
  <div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Créé le</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email || '—' }}</td>
            <td>{{ user.tel || '—' }}</td>
            <td>{{ formatDate(user.created_at) }}</td>
            <td class="actions">
              <button type="button" @click="router.push({ name: 'users.show', params: { id: user.id } })">Détails</button>
              <button type="button" @click="$emit('edit', user.id)">Modifier</button>
            </td>
          </tr>
          <tr v-if="users.length === 0"><td colspan="5" class="muted">Aucun utilisateur à afficher.</td></tr>
        </tbody>
      </table>
    </div>
    <BasePagination
      v-if="pagination"
      :current-page="pagination.current_page"
      :last-page="pagination.last_page"
      @change="$emit('page', $event)"
    />
  </div>
</template>

<style scoped>
button {
  padding: 0.45rem 0.65rem;
  border: 1px solid var(--color-border);
  border-radius: 0.45rem;
  background: white;
}
.actions { display: flex; gap: 0.5rem; }
</style>
