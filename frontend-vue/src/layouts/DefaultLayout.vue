<template>
  <div class="shell">
    <AppHeader />
    <p v-if="authError" class="auth-error" role="alert">{{ authError }}</p>
    <div class="body">
      <AppSidebar />
      <main class="content"><RouterView /></main>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import { responseMessage } from '@/services/api'

const auth = useAuthStore()
const authError = ref('')

onMounted(async () => {
  if (auth.user) return
  try {
    await auth.loadUser()
  } catch (requestError) {
    authError.value = responseMessage(requestError, 'Impossible de charger les droits du compte.')
  }
})
</script>

<style scoped>
.shell {
  min-height: 100vh;
  display: grid;
  grid-template-rows: auto 1fr auto;
}

.body {
  display: grid;
  grid-template-columns: 230px minmax(0, 1fr);
}

.content {
  min-width: 0;
  padding: 1.5rem;
}

.auth-error {
  margin: 0;
  padding: 0.65rem 1rem;
  background: #fff0ef;
  color: var(--color-danger);
}

@media (max-width: 720px) {
  .body { grid-template-columns: 1fr; }
  .body :deep(.sidebar) { border-right: 0; border-bottom: 1px solid var(--color-border); padding: 0.5rem; }
  .body :deep(nav) { display: flex; overflow-x: auto; }
  .content { padding: 0.75rem; }
}
</style>
