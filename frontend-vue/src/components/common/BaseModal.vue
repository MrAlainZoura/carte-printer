<script setup>
defineProps({ title: { type: String, required: true } })
defineEmits(['close'])
</script>

<template>
  <div class="backdrop" @click.self="$emit('close')">
    <section class="modal card" role="dialog" aria-modal="true" :aria-label="title">
      <div class="spread">
        <h2>{{ title }}</h2>
        <button class="close" type="button" aria-label="Fermer" @click="$emit('close')">×</button>
      </div>
      <slot />
    </section>
  </div>
</template>

<style scoped>
.backdrop {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  padding: 1rem;
  background: rgb(14 34 27 / 45%);
}

.modal {
  width: min(520px, 100%);
}

.close {
  border: 0;
  background: transparent;
  font-size: 1.5rem;
}
</style>
