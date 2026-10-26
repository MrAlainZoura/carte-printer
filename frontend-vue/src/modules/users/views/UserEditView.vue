<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import UserForm from '../components/UserForm.vue'
import { userService } from '../services/user.service'
import api, { responseMessage } from '@/services/api'

const props = defineProps({ id: { type: String, required: true } })
const router = useRouter()
const formData = ref(null)
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  try {
    const [data, postsResponse] = await Promise.all([
      userService.formData(props.id),
      api.get('/postes'),
    ])
    formData.value = { ...data, posts: postsResponse.data.data.posts }
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger cet utilisateur.')
  }
})

async function submit(payload) {
  loading.value = true
  error.value = ''
  try {
    await userService.update(props.id, payload)
    await router.push({ name: 'users.index' })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de modifier cet utilisateur.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>Modifier l’utilisateur</h1>
    <p v-if="error" class="alert">{{ error }}</p>
    <BaseLoader v-if="!formData && !error">Chargement de l’utilisateur…</BaseLoader>
    <div v-else-if="formData" class="card">
      <UserForm :initial-values="formData" :posts="formData.posts" :roles="formData.roles" editing :loading="loading" @submit="submit" />
    </div>
  </section>
</template>
