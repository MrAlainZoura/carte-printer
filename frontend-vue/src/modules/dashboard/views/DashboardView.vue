<script setup>
import { onMounted, ref } from 'vue'
import BaseLoader from '@/components/common/BaseLoader.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import api, { responseMessage } from '@/services/api'
import { usePostStore } from '@/modules/posts/stores/post.store'

const dashboard = ref(null)
const loading = ref(true)
const error = ref('')
const postStore = usePostStore()

function formatNumber(value) {
  return new Intl.NumberFormat('fr-FR').format(Number(value || 0))
}

function formatDate(value) {
  return value ? new Intl.DateTimeFormat('fr-FR').format(new Date(value)) : '—'
}

async function openPost(postId) {
  error.value = ''
  try {
    await postStore.openPost(postId)
    window.location.reload()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible d’ouvrir ce poste.')
  }
}

onMounted(async () => {
  try {
    const { data } = await api.get('/dashboard')
    dashboard.value = data.data
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le tableau de bord.')
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <DashboardLayout>
    <div class="spread">
      <div>
        <h1>{{ dashboard?.isGlobal ? 'Tableau de bord global' : `Tableau de bord${dashboard?.currentPost ? ` — ${dashboard.currentPost.libele}` : ''}` }}</h1>
        <p class="muted">{{ dashboard?.isGlobal ? 'Vue d’ensemble de tous les postes (accès administrateur).' : 'Indicateurs filtrés sur le poste en cours.' }}</p>
      </div>
        <RouterLink v-if="dashboard?.isGlobal" class="dashboard-link" to="/posts">Voir les postes →</RouterLink>
        <RouterLink v-else class="dashboard-link" to="/dashboard">Poste : {{ dashboard?.currentPost?.libele || 'Non renseigné' }}</RouterLink>
    </div>
    <BaseLoader v-if="loading">Chargement des indicateurs…</BaseLoader>
    <p v-else-if="error" class="alert">{{ error }}</p>
    <div v-else class="stats">
      <article v-for="stat in dashboard?.stats || []" :key="stat.label" class="card">
        <p class="muted">{{ stat.label }}</p>
        <strong>{{ formatNumber(stat.value) }}</strong>
        <small>{{ stat.hint }}</small>
        <RouterLink v-if="stat.route === 'vehicules.index'" to="/vehicles">Consulter →</RouterLink>
      </article>
    </div>
    <div v-if="dashboard?.supportingStats?.length" class="supporting-stats">
      <article v-for="stat in dashboard.supportingStats" :key="stat.label" class="card">
        <span>{{ stat.label }}</span><strong>{{ formatNumber(stat.value) }}</strong>
      </article>
    </div>
    <div class="dashboard-panels">
      <section class="card">
        <h2>Cartes roses enregistrées</h2>
        <p class="muted">Évolution des créations sur les six derniers mois.</p>
        <div class="monthly-chart" role="img" aria-label="Cartes roses créées par mois">
          <div v-for="month in dashboard?.requestsByMonth || []" :key="month.label" class="month">
            <span>{{ formatNumber(month.count) }}</span>
            <div class="bar" :style="{ height: `${Math.max(4, (month.count / Math.max(1, ...dashboard.requestsByMonth.map((item) => item.count))) * 100)}%` }" />
            <small>{{ month.label }}</small>
          </div>
        </div>
      </section>
      <section class="card">
        <h2>État des cartes</h2>
        <p class="muted">Répartition par statut enregistré.</p>
        <ul class="statuses">
          <li v-for="status in dashboard?.statuses || []" :key="status.key">
            <span><i :class="`status-dot ${status.key}`" />{{ status.label }}</span>
            <strong>{{ formatNumber(status.count) }}</strong>
          </li>
          <li v-if="!dashboard?.statuses?.length" class="muted">Aucune carte rose enregistrée.</li>
        </ul>
      </section>
    </div>
    <section v-if="dashboard?.isGlobal" class="card">
      <h2>Accéder à un poste de travail</h2>
      <p class="muted">Ouvrez un poste pour consulter ses opérations.</p>
      <div class="post-grid">
        <button v-for="post in dashboard.posts || []" :key="post.id" type="button" @click="openPost(post.id)">
          <span><strong>{{ post.libele }}</strong><small>{{ post.ville || 'Ville non renseignée' }}</small></span>
          <b>Ouvrir →</b>
        </button>
      </div>
      <p v-if="!dashboard.posts?.length" class="muted">Aucun poste enregistré.</p>
    </section>
    <section v-else class="card recent-cards">
      <div class="spread">
        <div><h2>Dernières cartes roses</h2><p class="muted">Les cinq dossiers les plus récemment créés.</p></div>
        <RouterLink class="dashboard-link" to="/vehicles">Consulter les véhicules →</RouterLink>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Référence</th><th>Propriétaire</th><th>Véhicule</th><th>Poste</th><th>Statut</th><th>Créée le</th></tr></thead>
          <tbody>
            <tr v-for="card in dashboard?.recentCards || []" :key="card.id">
              <td>{{ card.numero }}</td>
              <td>{{ card.vehicule?.proprietaire?.nom_complet || card.vehicule?.proprietaire?.raison_sociale || '—' }}</td>
              <td>{{ card.vehicule?.immatriculation || '—' }}</td>
              <td>{{ card.post_travail?.libele || '—' }}</td>
              <td><span class="status-badge" :class="card.statut">{{ card.statut }}</span></td>
              <td>{{ formatDate(card.created_at) }}</td>
            </tr>
            <tr v-if="!dashboard?.recentCards?.length"><td colspan="6" class="empty">Aucune carte rose enregistrée.</td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </DashboardLayout>
</template>

<style scoped>
.post {
  padding: 0.5rem 0.8rem;
  border-radius: 99px;
  background: #e8f3ee;
  color: var(--color-primary-dark);
  font-weight: 700;
}

.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 1rem;
  margin-top: 1.5rem;
}

.stats p { margin-top: 0; }
.stats strong { display: block; margin: 0.5rem 0; font-size: 2rem; }
.stats small { color: var(--color-muted); }
.dashboard-link { color: #1d4ed8; font-size: 0.9rem; font-weight: 600; }
.stats a { display: inline-block; margin-top: 1rem; color: #1d4ed8; font-size: 0.9rem; font-weight: 600; }
.supporting-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin: 1rem 0 2rem; }
.supporting-stats article { display: flex; justify-content: space-between; gap: 1rem; padding: 1rem; }
.supporting-stats span { color: var(--color-muted); }
.dashboard-panels { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem; }
.dashboard-panels h2, .recent-cards h2 { margin: 0; }
.monthly-chart { display: grid; height: 14rem; grid-template-columns: repeat(6, 1fr); gap: 0.75rem; align-items: end; margin-top: 1.5rem; }
.month { display: flex; height: 100%; flex-direction: column; justify-content: end; align-items: center; gap: 0.45rem; }
.month span { font-size: 0.8rem; font-weight: 600; }
.month small { color: var(--color-muted); font-size: 0.7rem; }
.bar { width: 70%; min-height: 4px; border-radius: 0.35rem 0.35rem 0 0; background: #2563eb; }
.statuses { display: grid; gap: 1rem; padding: 0; list-style: none; }
.statuses li, .statuses li span { display: flex; align-items: center; justify-content: space-between; gap: 0.6rem; }
.statuses .status-dot { width: 0.7rem; height: 0.7rem; border-radius: 50%; background: #2563eb; }
.status-dot.active { background: #22c55e; }.status-dot.expiree { background: #fb923c; }.status-dot.annulee { background: #ef4444; }.status-dot.perdue { background: #6b7280; }
.post-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem; margin-top: 1rem; }
.post-grid button { display: flex; justify-content: space-between; gap: 1rem; padding: 1rem; border: 1px solid var(--color-border); border-radius: 0.75rem; background: white; text-align: left; }
.post-grid span { display: grid; gap: 0.35rem; }.post-grid small { color: var(--color-muted); }.post-grid b { color: #1d4ed8; }
.recent-cards { margin-top: 1rem; }.recent-cards .spread { margin-bottom: 1rem; }.status-badge { border-radius: 99px; padding: 0.2rem 0.6rem; background: #e5e7eb; }.status-badge.active { color: #15803d; background: #dcfce7; }.status-badge.expiree { color: #c2410c; background: #ffedd5; }.status-badge.annulee { color: #b91c1c; background: #fee2e2; }
.empty { padding: 2rem; text-align: center; color: var(--color-muted); }
@media (max-width: 760px) { .dashboard-panels { grid-template-columns: 1fr; } }
</style>
