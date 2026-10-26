<script setup>
defineProps({ roles: { type: Array, default: () => [] } })
defineEmits(['show', 'edit', 'delete'])
</script>

<template>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Rôle</th><th>Permissions</th><th>Utilisateurs</th><th></th></tr></thead>
      <tbody>
        <tr v-for="role in roles" :key="role.id">
          <td>{{ role.name }}</td>
          <td>{{ role.permissions_count ?? role.permissions?.length ?? 0 }}</td>
          <td>{{ role.users_count ?? 0 }}</td>
          <td class="actions">
            <button type="button" @click="$emit('show', role.id)">Détails</button>
            <button type="button" @click="$emit('edit', role.id)">Modifier</button>
            <button type="button" :disabled="role.name === 'admin' || role.users_count > 0" @click="$emit('delete', role)">
              Supprimer
            </button>
          </td>
        </tr>
        <tr v-if="roles.length === 0"><td colspan="4" class="muted">Aucun rôle.</td></tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.actions {
  display: flex;
  gap: 0.5rem;
}

button {
  padding: 0.4rem 0.65rem;
}
</style>
