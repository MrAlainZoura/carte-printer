<script setup>
defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, required: true },
  name: { type: String, required: true },
  type: { type: String, default: 'text' },
  autocomplete: { type: String, default: undefined },
  required: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
  <label class="form-field" :for="name">
    <span>{{ label }}</span>
    <input
      :id="name"
      :name="name"
      :type="type"
      :value="modelValue"
      :autocomplete="autocomplete"
      :required="required"
      @input="$emit('update:modelValue', $event.target.value)"
    />
  </label>
</template>
