<script setup>
import { onMounted, ref } from 'vue'
import BaseLoader from '@/components/common/BaseLoader.vue'
import { profileService } from '../services/profile.service'
import { responseMessage } from '@/services/api'

const profile = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref('')
const form = ref({ current_password: '', password: '', password_confirmation: '' })

onMounted(async () => {
  try {
    profile.value = await profileService.show()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le profil.')
  }
})

async function updatePassword() {
  error.value = ''
  success.value = ''
  loading.value = true
  try {
    const result = await profileService.updatePassword(form.value)
    success.value = result.message
    form.value = { current_password: '', password: '', password_confirmation: '' }
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de modifier le mot de passe.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="page">
    <h1>Mon profil</h1>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <p v-if="success" class="success" role="status">{{ success }}</p>
    <BaseLoader v-if="!profile && !error">Chargement du profil…</BaseLoader>
    <template v-else-if="profile">
      <div class="card profile">
        <h2>{{ profile.record.name }}</h2>
        <p>{{ profile.record.email || 'Email non renseigné' }}</p>
        <p>{{ profile.record.tel || 'Téléphone non renseigné' }}</p>
        <p>Rôle : {{ profile.record.roles?.[0]?.name || 'utilisateur' }}</p>
        <p>Postes : {{ profile.record.post_travails?.map((post) => post.libele).join(', ') || 'Aucun' }}</p>
        <ul><li v-for="(value, label) in profile.stats" :key="label">{{ label }} : {{ value }}</li></ul>
      </div>
      <form class="card password-form" @submit.prevent="updatePassword">
        <h2>Modifier mon mot de passe</h2>
        <label class="form-field"><span>Mot de passe actuel</span><input v-model="form.current_password" type="password" required autocomplete="current-password" /></label>
        <label class="form-field"><span>Nouveau mot de passe</span><input v-model="form.password" type="password" required minlength="8" autocomplete="new-password" /></label>
        <label class="form-field"><span>Confirmer le mot de passe</span><input v-model="form.password_confirmation" type="password" required minlength="8" autocomplete="new-password" /></label>
        <button type="submit" :disabled="loading">{{ loading ? 'Mise à jour…' : 'Mettre à jour' }}</button>
      </form>
    </template>
  </section>
</template>

<style scoped>
.profile, .password-form { margin-bottom: 1rem; }
.password-form { display: grid; gap: 1rem; }
.form-field { display: grid; max-width: 30rem; gap: 0.4rem; }
input { min-height: 2.6rem; padding: 0.55rem 0.7rem; }
button { justify-self: start; min-height: 2.6rem; padding: 0.5rem 0.9rem; }
</style>
