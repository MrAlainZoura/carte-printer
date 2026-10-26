<script setup>
import { computed, onMounted, ref } from 'vue'
import { responseMessage } from '@/services/api'
import { usePostStore } from '@/modules/posts/stores/post.store'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const posts = usePostStore()
const auth = useAuthStore()
const error = ref('')
const selectedId = ref(posts.currentPostId)
const initialPostName = ref('')
const initialPostUsesCdf = ref(true)
const canGlobal = computed(() => (auth.user?.permissions || []).some((permission) => permission.name === 'dashboard.global')
  || (auth.user?.roles || []).some((role) => role.permissions?.some((permission) => permission.name === 'dashboard.global')))

onMounted(async () => {
  try {
    await posts.fetchOptions()
    selectedId.value = posts.currentPostId
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les postes de travail.')
  }
})

async function selectPost() {
  error.value = ''
  try {
    await posts.selectPost(selectedId.value)
    window.location.reload()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de sélectionner ce poste.')
    selectedId.value = posts.currentPostId
  }
}

async function selectGlobal() {
  error.value = ''
  try {
    await posts.selectGlobal()
    window.location.reload()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'La vue globale n’est pas autorisée.')
  }
}

async function createInitialPost() {
  error.value = ''
  try {
    await posts.createInitialPost({
      libele: initialPostName.value,
      use_cdf: initialPostUsesCdf.value,
    })
    window.location.reload()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de créer le poste de travail.')
  }
}
</script>

<template>
  <div class="post-selector">
    <p v-if="error" class="post-error" role="alert">{{ error }}</p>
    <form v-else-if="posts.needsInitialSetup" class="initial-post-form" @submit.prevent="createInitialPost">
      <input v-model.trim="initialPostName" aria-label="Nom du premier poste" placeholder="Nom du poste" required />
      <label><input v-model="initialPostUsesCdf" type="checkbox" /> Devise CDF</label>
      <button type="submit">Créer le poste</button>
    </form>
    <div v-else class="post-controls">
      <label class="sr-only" for="current-post">Poste de travail</label>
      <select
        id="current-post"
        v-model="selectedId"
        :disabled="posts.loading || posts.posts.length === 0"
        @change="selectPost"
      >
        <option value="" disabled>Sélectionner un poste</option>
        <option v-for="post in posts.posts" :key="post.id" :value="String(post.id)">
          {{ post.libele }}
        </option>
      </select>
      <button v-if="posts.isAdmin && canGlobal" type="button" @click="selectGlobal">
        Vue globale
      </button>
    </div>
  </div>
</template>

<style scoped>
.post-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

select,
input:not([type="checkbox"]),
button {
  min-height: 2.4rem;
  padding: 0.4rem 0.65rem;
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
  background: white;
  color: inherit;
}

.post-hint,
.post-error {
  margin: 0;
  font-size: 0.85rem;
}

.initial-post-form {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.initial-post-form label {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  white-space: nowrap;
  font-size: 0.85rem;
}

.post-error {
  color: var(--color-danger);
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

@media (max-width: 760px) {
  select {
    max-width: 10rem;
  }
}
</style>
