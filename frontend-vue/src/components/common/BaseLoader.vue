<template>
  <div class="loader" role="status" aria-live="polite">
    <span class="spinner" aria-hidden="true"></span>
    <span><slot>Chargement…</slot></span>
  </div>
</template>

<style scoped>
.loader {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.7rem;
  padding: 2rem;
  color: var(--color-muted);
}

.spinner {
  width: 1.2rem;
  height: 1.2rem;
  border: 2px solid var(--color-border);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
