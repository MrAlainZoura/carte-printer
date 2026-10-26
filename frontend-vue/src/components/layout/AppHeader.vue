<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import { responseMessage } from '@/services/api'
import PostSelector from './PostSelector.vue'

const auth = useAuthStore()
const error = ref('')

async function logout() {
  error.value = ''
  try {
    await auth.logout()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'La session locale a été fermée, mais le jeton n’a pas pu être révoqué.')
  }
}
</script>

<template>
  <header class="header">
    <RouterLink class="brand" to="/dashboard">CarteRose</RouterLink>
    <div class="row">
      <PostSelector />
      <span class="welcome">{{ auth.user?.name || auth.user?.email }}</span>
      <button type="button" @click="logout">Déconnexion</button>
    </div>
  </header>
  <p v-if="error" class="logout-error" role="alert">{{ error }}</p>
</template>

<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 4rem;
  padding: 0 1.5rem;
  border-bottom: 1px solid var(--color-border);
  background: white;
}

.brand {
  color: var(--color-primary-dark);
  font-size: 1.25rem;
  font-weight: 800;
}

button {
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
  background: white;
}

.logout-error {
  margin: 0;
  padding: 0.65rem 1.5rem;
  background: #fff0ef;
  color: var(--color-danger);
}

@media (max-width: 480px) {
  .welcome { display: none; }
}
</style>
