<script setup>
defineProps({
  currentPage: { type: Number, default: 1 },
  lastPage: { type: Number, default: 1 },
})
defineEmits(['change'])
</script>

<template>
  <nav class="pagination" aria-label="Pagination">
    <button :disabled="currentPage <= 1" @click="$emit('change', currentPage - 1)">Précédent</button>
    <span>Page {{ currentPage }} / {{ lastPage }}</span>
    <button :disabled="currentPage >= lastPage" @click="$emit('change', currentPage + 1)">Suivant</button>
  </nav>
</template>

<style scoped>
.pagination {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

button {
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
  background: white;
}

button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>
