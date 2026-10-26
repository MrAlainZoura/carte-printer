<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  initialValues: { type: Object, default: () => ({}) },
  editing: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
})
const emit = defineEmits(['submit'])
const form = reactive({ name: '', permissions: [] })

watch(() => props.initialValues, (values) => {
  form.name = values.role?.name || ''
  form.permissions = [...(values.selectedPermissions || [])]
}, { immediate: true })
</script>

<template>
  <form class="role-form" @submit.prevent="emit('submit', { name: form.name, permissions: [...form.permissions] })">
    <label class="form-field">
      <span>Nom technique du rôle</span>
      <input
        v-model.trim="form.name"
        name="name"
        required
        maxlength="125"
        pattern="[a-z0-9_-]+"
        :disabled="editing && initialValues.role?.name === 'admin'"
      />
      <small v-if="editing && initialValues.role?.name === 'admin'">Le rôle administrateur ne peut pas être renommé.</small>
      <small v-else>Minuscules, chiffres, tirets et tirets bas uniquement.</small>
    </label>
    <fieldset v-for="group in initialValues.permissionGroups || []" :key="group.key">
      <legend>{{ group.label }}</legend>
      <label v-for="permission in group.permissions" :key="permission.name" class="permission">
        <input v-model="form.permissions" type="checkbox" :value="permission.name" />
        <span>{{ permission.label }}</span>
      </label>
    </fieldset>
    <button type="submit" :disabled="loading">{{ loading ? 'Enregistrement…' : editing ? 'Enregistrer' : 'Créer le rôle' }}</button>
  </form>
</template>

<style scoped>
.role-form {
  display: grid;
  gap: 1rem;
}

input:not([type="checkbox"]) {
  min-height: 2.6rem;
  padding: 0.5rem 0.7rem;
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
}

fieldset {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem 1.25rem;
  border: 1px solid var(--color-border);
  border-radius: 0.6rem;
}

.permission {
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

button {
  justify-self: start;
  min-height: 2.6rem;
  padding: 0.5rem 0.9rem;
}
</style>
