<script setup>
import { onMounted, reactive, ref } from 'vue'
import { authService } from '../services/auth.service'
import { responseMessage } from '@/services/api'

const options = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref('')
const form = reactive({
  name: '',
  email: '',
  tel: '',
  post_travail_id: '',
  role: '',
  password: '',
  password_confirmation: '',
})

onMounted(async () => {
  try {
    options.value = await authService.registrationOptions()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les options d’inscription.')
  }
})

async function submit() {
  loading.value = true
  error.value = ''
  success.value = ''
  try {
    const result = await authService.register({ ...form })
    success.value = result.message
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de créer le compte.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="card auth-card stack">
    <div>
      <p class="eyebrow">NOUVEAU COMPTE</p>
      <h1>Créer un compte</h1>
      <p class="muted">Inscrivez-vous avec le poste de travail et le rôle qui vous sont attribués.</p>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <div v-if="success" class="success-panel" role="status">
      <p>{{ success }}</p>
      <RouterLink class="link" to="/login">Se connecter</RouterLink>
    </div>
    <p v-else-if="!options && !error" class="muted">Chargement des options…</p>
    <template v-else-if="options">
      <p v-if="options.posts.length === 0" class="alert" role="alert">
        Aucun poste actif n’est disponible pour l’inscription. Contactez un administrateur.
      </p>
      <form v-else class="registration-form" @submit.prevent="submit">
        <label>
          <span>Nom complet</span>
          <input v-model.trim="form.name" name="name" autocomplete="name" required maxlength="255" />
        </label>
        <label>
          <span>Adresse e-mail</span>
          <input v-model.trim="form.email" name="email" type="email" autocomplete="email" required maxlength="255" />
        </label>
        <label>
          <span>Téléphone (+243…)</span>
          <input v-model.trim="form.tel" name="tel" type="tel" autocomplete="tel" required pattern="\+[1-9][0-9]{7,14}" />
        </label>
        <label>
          <span>Poste de travail</span>
          <select v-model="form.post_travail_id" name="post_travail_id" required>
            <option value="" disabled>Sélectionner un poste</option>
            <option v-for="post in options.posts" :key="post.id" :value="post.id">{{ post.libele }}</option>
          </select>
        </label>
        <label>
          <span>Rôle</span>
          <select v-model="form.role" name="role" required>
            <option value="" disabled>Sélectionner un rôle</option>
            <option v-for="(label, role) in options.roles" :key="role" :value="role">{{ label }}</option>
          </select>
        </label>
        <label>
          <span>Mot de passe</span>
          <input v-model="form.password" name="password" type="password" autocomplete="new-password" required minlength="8" />
        </label>
        <label>
          <span>Confirmer le mot de passe</span>
          <input v-model="form.password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8" />
        </label>
        <button type="submit" :disabled="loading">
          {{ loading ? 'Création du compte…' : 'Créer mon compte' }}
        </button>
      </form>
    </template>
    <p class="login-link">Vous avez déjà un compte ? <RouterLink class="link" to="/login">Se connecter</RouterLink></p>
  </section>
</template>

<style scoped>
.eyebrow { margin-bottom: 0.3rem; color: var(--color-primary); font-size: 0.75rem; font-weight: 800; letter-spacing: 0.1em; }
h1 { margin-bottom: 0.4rem; }
.registration-form { display: grid; gap: 1rem; }
.registration-form label { display: grid; gap: 0.4rem; }
input, select { min-height: 2.7rem; padding: 0.55rem 0.7rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background: white; }
button { min-height: 2.7rem; padding: 0.6rem 1rem; border: 0; border-radius: 0.5rem; background: var(--color-primary); color: white; font-weight: 700; }
button:disabled { cursor: wait; opacity: 0.65; }
.link { color: var(--color-primary); font-weight: 700; }
.login-link { margin: 0; text-align: center; color: var(--color-muted); }
.success-panel { padding: 1rem; border-radius: 0.5rem; background: #f0fdf4; color: #166534; }
.success-panel p { margin-top: 0; }
</style>
