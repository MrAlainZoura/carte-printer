<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import { userService } from '../services/user.service'
import { responseMessage } from '@/services/api'

const props = defineProps({ id: { type: String, required: true } })
const router = useRouter()
const user = ref(null)
const error = ref('')

onMounted(async () => {
  try {
    user.value = await userService.show(props.id)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger cet utilisateur.')
  }
})
</script>

<template>
  <section class="page">
    <div class="spread">
      <h1>Profil de l’utilisateur</h1>
      <button type="button" @click="router.push({ name: 'users.edit', params: { id } })">Modifier</button>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <BaseLoader v-if="!user && !error">Chargement…</BaseLoader>
    <template v-else-if="user">
      <section class="card identity">
        <h2>{{ user.record.name }}</h2>
        <p>{{ user.record.email || 'Email non renseigné' }}</p>
        <p>{{ user.record.tel || 'Téléphone non renseigné' }}</p>
        <p>Rôles : {{ user.record.roles?.map((role) => role.name).join(', ') || 'Aucun' }}</p>
        <p>Postes : {{ user.record.post_travails?.map((post) => post.libele).join(', ') || 'Aucun' }}</p>
      </section>
      <section class="user-stats">
        <article v-for="(value, label) in user.stats" :key="label" class="card">
          <span>{{ label }}</span><strong>{{ value }}</strong>
        </article>
      </section>
    </template>
  </section>
</template>

<style scoped>
.identity { margin-bottom: 1rem; }
.user-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
.user-stats article { display: grid; gap: 0.5rem; }
.user-stats span { color: var(--color-muted); }
.user-stats strong { font-size: 1.6rem; }
button { padding: 0.5rem 0.8rem; }
</style>
