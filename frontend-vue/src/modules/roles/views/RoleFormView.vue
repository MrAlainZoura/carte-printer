<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import RoleForm from '../components/RoleForm.vue'
import { roleService } from '../services/role.service'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const initialValues = ref(null)
const loading = ref(false)
const error = ref('')
const editing = Boolean(route.params.id)

onMounted(async () => {
  try {
    initialValues.value = await roleService.formData(route.params.id)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le formulaire du rôle.')
  }
})

async function submit(payload) {
  loading.value = true
  error.value = ''
  try {
    const result = editing
      ? await roleService.update(route.params.id, payload)
      : await roleService.create(payload)
    if (result.updated === false) {
      error.value = result.message
      return
    }
    await router.push({ name: 'roles.index', query: { message: result.message } })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible d’enregistrer le rôle.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>{{ editing ? 'Modifier le rôle' : 'Créer un rôle' }}</h1>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!initialValues && !error">Chargement du formulaire…</BaseLoader>
    <div v-else-if="initialValues" class="card">
      <RoleForm :initial-values="initialValues" :editing="editing" :loading="loading" @submit="submit" />
    </div>
  </section>
</template>
