<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '@/components/common/BaseButton.vue'
import BaseLoader from '@/components/common/BaseLoader.vue'
import UserTable from '../components/UserTable.vue'
import { useUserStore } from '../stores/user.store'
import { responseMessage } from '@/services/api'

const router = useRouter()
const store = useUserStore()
const error = ref('')

async function load(page = 1) {
  error.value = ''
  try {
    await store.fetch({ page })
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les utilisateurs.')
  }
}

onMounted(() => load())
</script>

<template>
  <section class="page">
    <div class="spread">
      <div><h1>Utilisateurs</h1><p class="muted">Comptes et accès aux postes.</p></div>
      <BaseButton @click="router.push({ name: 'users.create' })">Créer un utilisateur</BaseButton>
    </div>
    <p v-if="error" class="alert">{{ error }}</p>
    <BaseLoader v-else-if="store.loading">Chargement des utilisateurs…</BaseLoader>
    <div v-else class="card">
      <UserTable :users="store.users" :pagination="store.pagination" @page="load" @edit="router.push(`/users/${$event}/edit`)" />
    </div>
  </section>
</template>
