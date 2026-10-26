<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import { roleService } from '../services/role.service'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const role = ref(null)
const error = ref('')

onMounted(async () => {
  try {
    role.value = await roleService.show(route.params.id)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger ce rôle.')
  }
})
</script>

<template>
  <section class="page">
    <div class="spread">
      <h1>Détails du rôle</h1>
      <button v-if="role" type="button" @click="router.push(`/roles/${role.role.id}/edit`)">Modifier</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!role && !error">Chargement du rôle…</BaseLoader>
    <template v-else-if="role">
      <div class="card">
        <h2>{{ role.role.name }}</h2>
        <p>{{ role.role.users?.length || 0 }} utilisateur(s)</p>
        <h3>Permissions</h3>
        <section v-for="group in role.permissionGroups" :key="group.key">
          <h4>{{ group.label }}</h4>
          <ul>
            <li v-for="permission in group.permissions.filter((item) => role.role.permissions.some((selected) => selected.name === item.name))" :key="permission.name">
              {{ permission.label }}
            </li>
          </ul>
        </section>
      </div>
    </template>
  </section>
</template>
