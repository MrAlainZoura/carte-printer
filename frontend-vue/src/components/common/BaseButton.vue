<script setup>
defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' },
  disabled: { type: Boolean, default: false },
})
</script>

<template>
  <button class="base-button" :class="`base-button--${variant}`" :type="type" :disabled="disabled">
    <slot />
  </button>
</template>

<style scoped>
.base-button {
  min-height: 2.65rem;
  padding: 0.6rem 1rem;
  border: 0;
  border-radius: 0.55rem;
  font-weight: 650;
}

.base-button--primary {
  background: var(--color-primary);
  color: white;
}

.base-button--secondary {
  border: 1px solid var(--color-border);
  background: white;
  color: inherit;
}

.base-button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}
</style>
