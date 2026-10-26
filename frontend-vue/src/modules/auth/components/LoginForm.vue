<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '@/components/common/BaseButton.vue'
import BaseInput from '@/components/common/BaseInput.vue'
import { responseMessage } from '@/services/api'
import { useAuthStore } from '../stores/auth.store'

const router = useRouter()
const auth = useAuthStore()
const identifiant = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function submit() {
  loading.value = true
  error.value = ''
  try {
    await auth.login({ identifiant: identifiant.value, password: password.value })
    await router.replace({ name: 'dashboard' })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Connexion impossible.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <form class="stack" @submit.prevent="submit">
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseInput v-model="identifiant" name="identifiant" label="Email, téléphone ou nom" autocomplete="username" required />
    <BaseInput v-model="password" name="password" label="Mot de passe" type="password" autocomplete="current-password" required />
    <BaseButton type="submit" class="full-width" :disabled="loading">
      {{ loading ? 'Connexion…' : 'Se connecter' }}
    </BaseButton>
  </form>
</template>
