<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const auth = useAuthStore()
const permissions = computed(() => [
  ...(auth.user?.permissions || []).map((permission) => permission.name),
  ...(auth.user?.roles || []).flatMap((role) => (role.permissions || []).map((permission) => permission.name)),
])
const isAdmin = computed(() => (auth.user?.roles || []).some((role) => role.name === 'admin'))
const can = (permission) => permissions.value.includes(permission)
</script>

<template>
  <aside class="sidebar">
    <nav>
      <RouterLink to="/dashboard">Tableau de bord</RouterLink>
      <RouterLink v-if="can('vehicule.view')" to="/vehicles">Véhicules</RouterLink>
      <RouterLink v-if="can('produit.view')" to="/products">Produits</RouterLink>
      <RouterLink v-if="can('post_produit.view')" to="/stock">Produits des postes</RouterLink>
      <RouterLink v-if="can('approvisionnement.view')" to="/supplies">Approvisionnements</RouterLink>
      <RouterLink v-if="can('post_travail.view')" to="/posts">Postes de travail</RouterLink>
      <RouterLink v-if="can('proprietaire.view')" to="/owners">Propriétaires</RouterLink>
      <RouterLink v-if="isAdmin && can('user.view')" to="/users">Utilisateurs</RouterLink>
      <RouterLink v-if="can('role.manage')" to="/roles">Rôles et permissions</RouterLink>
      <RouterLink to="/profile">Mon profil</RouterLink>
    </nav>
  </aside>
</template>

<style scoped>
.sidebar {
  padding: 1.25rem 0.75rem;
  border-right: 1px solid var(--color-border);
  background: white;
}

nav {
  display: grid;
  gap: 0.35rem;
}

a {
  padding: 0.75rem;
  border-radius: 0.55rem;
  color: var(--color-muted);
}

a.router-link-active {
  background: #e8f3ee;
  color: var(--color-primary-dark);
  font-weight: 700;
}
</style>
