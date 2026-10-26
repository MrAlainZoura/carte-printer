<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  fields: { type: Array, required: true },
  initialValues: { type: Object, default: () => ({}) },
  options: { type: Object, default: () => ({}) },
  loading: { type: Boolean, default: false },
})
const emit = defineEmits(['submit'])
const form = reactive({})

watch(
  () => [props.fields, props.initialValues],
  () => {
    for (const field of props.fields) {
      const value = props.initialValues?.[field.key]
      form[field.key] = field.type === 'checkbox'
        ? Boolean(value)
        : field.type === 'multiselect'
          ? [...(value || [])]
          : value ?? ''
    }
  },
  { immediate: true },
)

function optionsFor(field) {
  const options = props.options[field.optionsKey]
  if (!options) return field.options || []
  if (Array.isArray(options)) return options
  return Object.entries(options).map(([value, label]) => ({ value: Number(value), label }))
}

function optionValue(field, option) {
  return typeof option === 'object' ? option.value ?? option.id : option
}

function optionLabel(field, option) {
  return typeof option === 'object' ? option[field.optionLabel || 'label'] ?? option.name ?? option.libele : option
}
</script>

<template>
  <form class="resource-form" @submit.prevent="emit('submit', { ...form })">
    <label v-for="field in fields" :key="field.key" class="form-field">
      <span>{{ field.label }}</span>
      <textarea
        v-if="field.type === 'textarea'"
        v-model="form[field.key]"
        :required="field.required"
        rows="3"
      />
      <select
        v-else-if="['select', 'selectMap', 'selectList'].includes(field.type)"
        v-model="form[field.key]"
        :required="field.required"
      >
        <option value="" disabled>Sélectionner…</option>
        <option v-for="option in optionsFor(field)" :key="optionValue(field, option)" :value="optionValue(field, option)">
          {{ optionLabel(field, option) }}
        </option>
      </select>
      <select v-else-if="field.type === 'multiselect'" v-model="form[field.key]" multiple>
        <option v-for="option in optionsFor(field)" :key="option.id" :value="option.id">
          {{ optionLabel(field, option) }}
        </option>
      </select>
      <input
        v-else
        v-model="form[field.key]"
        :type="field.type || 'text'"
        :required="field.required"
      />
    </label>
    <button type="submit" :disabled="loading">{{ loading ? 'Enregistrement…' : 'Enregistrer' }}</button>
  </form>
</template>

<style scoped>
.resource-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1rem; }
.form-field { display: grid; align-content: start; gap: 0.4rem; }
input, select, textarea { min-height: 2.6rem; padding: 0.55rem 0.7rem; border: 1px solid var(--color-border); border-radius: 0.5rem; }
select[multiple] { min-height: 8rem; }
input[type="checkbox"] { min-height: auto; justify-self: start; }
button { align-self: end; justify-self: start; min-height: 2.6rem; padding: 0.5rem 0.9rem; }
</style>
