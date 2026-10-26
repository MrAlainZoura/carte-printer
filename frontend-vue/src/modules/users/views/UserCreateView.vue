<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import UserForm from '../components/UserForm.vue'
import { userService } from '../services/user.service'
import api, { responseMessage } from '@/services/api'

const router = useRouter()
const formData = ref(null)
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  try {
    const [data, postsResponse] = await Promise.all([
      userService.formData(),
      api.get('/postes'),
    ])
    formData.value = { ...data, posts: postsResponse.data.data.posts }
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le formulaire.')
  }
})

async function submit(payload) {
  loading.value = true
  error.value = ''
  try {
    await userService.create(payload)
    await router.push({ name: 'users.index' })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de créer l’utilisateur.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>Créer un utilisateur</h1>
    <p v-if="error" class="alert">{{ error }}</p>
    <BaseLoader v-if="!formData && !error">Chargement du formulaire…</BaseLoader>
    <div v-else-if="formData" class="card">
      <UserForm :initial-values="formData" :posts="formData.posts" :roles="formData.roles" :loading="loading" @submit="submit" />
    </div>
  </section>
</template>
