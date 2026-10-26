<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import RoleTable from '../components/RoleTable.vue'
import { useRoleStore } from '../stores/role.store'
import { roleService } from '../services/role.service'
import { responseMessage } from '@/services/api'

const router = useRouter()
const route = useRoute()
const store = useRoleStore()
const error = ref('')
const success = ref(route.query.message || '')

async function load() {
  error.value = ''
  try {
    await store.fetch()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les rôles.')
  }
}

async function remove(role) {
  if (!window.confirm(`Supprimer le rôle « ${role.name} » ?`)) return
  error.value = ''
  success.value = ''
  try {
    const result = await roleService.remove(role.id)
    if (result.deleted === false) {
      error.value = result.message
      return
    }
    await load()
    success.value = result.message
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de supprimer ce rôle.')
  }
}

onMounted(load)
</script>

<template>
  <section class="page">
    <div class="spread">
      <div><h1>Rôles et permissions</h1><p class="muted">Gérer les droits d’accès.</p></div>
      <button type="button" @click="router.push({ name: 'roles.create' })">Créer un rôle</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <p v-if="success" class="success" role="status">{{ success }}</p>
    <BaseLoader v-if="store.loading">Chargement des rôles…</BaseLoader>
    <div v-else class="card">
      <RoleTable :roles="store.roles" @show="router.push(`/roles/${$event}`)" @edit="router.push(`/roles/${$event}/edit`)" @delete="remove" />
    </div>
  </section>
</template>
