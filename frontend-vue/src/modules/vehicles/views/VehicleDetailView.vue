<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseLoader from '@/components/common/BaseLoader.vue'
import BasePagination from '@/components/common/BasePagination.vue'
import { vehicleService } from '../services/vehicle.service'
import { responseMessage } from '@/services/api'

const route = useRoute()
const router = useRouter()
const vehicle = ref(null)
const history = ref(null)
const ownerOptions = ref([])
const plateCode = ref('')
const replacementPlate = ref({ code: '', ville: '', serie: '' })
const editingPlate = ref(null)
const editingCard = ref(null)
const newOwnerId = ref('')
const error = ref('')
const success = ref('')
const busy = ref(false)

async function load() {
  try {
    vehicle.value = await vehicleService.show(route.params.id)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger ce véhicule.')
  }
}

onMounted(load)

async function loadOwnerOptions() {
  try {
    const result = await vehicleService.ownerOptions(route.params.id)
    ownerOptions.value = result.proprietaires || []
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger les propriétaires.')
  }
}

async function loadHistory() {
  try {
    const result = await vehicleService.ownerHistory(route.params.id)
    history.value = result.historique
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger l’historique.')
  }
}

async function loadHistoryPage(page) {
  try {
    const result = await vehicleService.ownerHistory(route.params.id, { page })
    history.value = result.historique
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger l’historique.')
  }
}

async function transferOwner() {
  busy.value = true
  error.value = ''
  try {
    const result = await vehicleService.transferOwner(route.params.id, newOwnerId.value)
    success.value = result.message
    newOwnerId.value = ''
    await load()
    await loadHistory()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de transférer la propriété.')
  } finally {
    busy.value = false
  }
}

async function changePlate() {
  busy.value = true
  error.value = ''
  try {
    const result = await vehicleService.changePlate(route.params.id, replacementPlate.value)
    success.value = result.message
    replacementPlate.value = { code: '', ville: '', serie: '' }
    await load()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de remplacer la plaque.')
  } finally {
    busy.value = false
  }
}

async function loadPlateForm() {
  try {
    const data = await vehicleService.changePlateForm(route.params.id)
    plateCode.value = data.vehicule?.plaque?.code || ''
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger le formulaire de plaque.')
  }
}

async function updatePlate() {
  busy.value = true
  error.value = ''
  try {
    const result = await vehicleService.updatePlate(route.params.id, vehicle.value.plaque.id, editingPlate.value)
    success.value = result.message
    editingPlate.value = null
    await load()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de modifier les informations de plaque.')
  } finally {
    busy.value = false
  }
}

async function loadPlateEdit() {
  try {
    const data = await vehicleService.plateForm(route.params.id, vehicle.value.plaque.id)
    editingPlate.value = {
      ville: data.plaque.ville || '',
      serie: data.plaque.serie || '',
      statut: data.plaque.statut || 'attribuee',
      date_attribution: data.plaque.date_attribution || '',
    }
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger la plaque.')
  }
}

async function updateCard(card) {
  busy.value = true
  error.value = ''
  try {
    const result = await vehicleService.updateCard(route.params.id, card.id, card)
    success.value = result.message
    editingCard.value = null
    await load()
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de modifier la carte rose.')
  } finally {
    busy.value = false
  }
}

async function loadCardEdit(card) {
  try {
    const data = await vehicleService.cardForm(route.params.id, card.id)
    editingCard.value = { ...data.carteRose }
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de charger la carte rose.')
  }
}

async function printCard() {
  error.value = ''
  try {
    const pdf = await vehicleService.printCard(route.params.id)
    const url = URL.createObjectURL(pdf)
    window.open(url, '_blank', 'noopener')
    window.setTimeout(() => URL.revokeObjectURL(url), 60_000)
  } catch (requestError) {
    error.value = responseMessage(requestError, 'Impossible de générer la carte rose.')
  }
}
</script>

<template>
  <section class="page">
    <div class="spread">
      <h1>Détails du véhicule</h1>
      <div class="owner-actions">
        <a href="#owner-transfer">Changer de propriétaire</a>
        <a href="#owner-history" @click="loadHistory">Historique des propriétaires</a>
        <button type="button" @click="router.push(`/vehicles/${route.params.id}/edit`)">Modifier</button>
      </div>
    </div>
    <p v-if="error" class="alert" role="alert">{{ error }}</p>
    <p v-if="success" role="status">{{ success }}</p>
    <BaseLoader v-if="!vehicle && !error">Chargement du véhicule…</BaseLoader>
    <template v-else-if="vehicle">
      <dl class="card details">
        <template v-for="(label, key) in {
          immatriculation: 'Immatriculation', marque: 'Marque', type: 'Type', couleur: 'Couleur',
          usage: 'Usage', etat: 'État', an_fabrication: 'Année de fabrication', an_circulation: 'Année de circulation',
          moteur: 'Moteur', puissance: 'Puissance', chassis: 'Châssis', cartinspection: 'Carte d’inspection'
        }" :key="key">
          <dt>{{ label }}</dt><dd>{{ vehicle[key] || '—' }}</dd>
        </template>
        <dt>Propriétaire</dt><dd>{{ vehicle.proprietaire?.nom_complet || vehicle.proprietaire?.raison_sociale || '—' }}</dd>
        <dt>Poste</dt><dd>{{ vehicle.post_travail?.libele || '—' }}</dd>
      </dl>
      <div v-if="vehicle.plaque" class="card">
        <h2>Plaque {{ vehicle.plaque.code }}</h2>
        <button v-if="!editingPlate" type="button" @click="loadPlateEdit">Modifier les informations de plaque</button>
        <form v-else class="inline-form" @submit.prevent="updatePlate">
          <label>Ville <input v-model="editingPlate.ville" /></label>
          <label>Série <input v-model="editingPlate.serie" /></label>
          <label>Statut
            <select v-model="editingPlate.statut">
              <option value="disponible">Disponible</option><option value="attribuee">Attribuée</option><option value="annulee">Annulée</option>
            </select>
          </label>
          <label>Date d’attribution <input v-model="editingPlate.date_attribution" type="date" /></label>
          <button type="submit" :disabled="busy">Enregistrer</button>
        </form>
        <button type="button" @click="loadPlateForm">Préparer un remplacement de plaque</button>
        <form v-if="plateCode" class="inline-form" @submit.prevent="changePlate">
          <label>Nouvelle immatriculation <input v-model.trim="replacementPlate.code" required /></label>
          <label>Ville <input v-model.trim="replacementPlate.ville" /></label>
          <label>Série <input v-model.trim="replacementPlate.serie" /></label>
          <button type="submit" :disabled="busy">Remplacer (stock de plaque requis)</button>
        </form>
      </div>
      <div v-if="vehicle.carte_roses?.length" class="card">
        <h2>Cartes roses</h2>
        <button type="button" @click="printCard">Imprimer la carte PDF</button>
        <article v-for="card in vehicle.carte_roses" :key="card.id">
          <p>{{ card.numero }} — {{ card.statut }}</p>
          <button v-if="editingCard?.id !== card.id" type="button" @click="loadCardEdit(card)">Modifier</button>
          <form v-else class="inline-form" @submit.prevent="updateCard(editingCard)">
            <label>Libellé <input v-model="editingCard.libele" required /></label>
            <label>Numéro <input v-model="editingCard.numero" required /></label>
            <label>Date d’octroi <input v-model="editingCard.date_octroi" type="date" /></label>
            <label>Date d’expiration <input v-model="editingCard.date_expiration" type="date" /></label>
            <label>Statut
              <select v-model="editingCard.statut">
                <option value="active">Active</option><option value="expiree">Expirée</option><option value="annulee">Annulée</option><option value="perdue">Perdue</option>
              </select>
            </label>
            <label>Observations <textarea v-model="editingCard.observations" /></label>
            <button type="submit" :disabled="busy">Enregistrer</button>
          </form>
        </article>
      </div>
      <div class="card">
        <h2 id="owner-transfer">Transférer le propriétaire</h2>
        <button type="button" @click="loadOwnerOptions">Charger les propriétaires</button>
        <form v-if="ownerOptions.length" class="transfer" @submit.prevent="transferOwner">
          <select v-model="newOwnerId" required>
            <option value="" disabled>Choisir un propriétaire</option>
            <option v-for="owner in ownerOptions" :key="owner.id" :value="owner.id">
              {{ owner.nom_complet || owner.raison_sociale || `#${owner.id}` }}
            </option>
          </select>
          <button type="submit" :disabled="busy">Transférer</button>
        </form>
        <h3 id="owner-history">Historique des propriétaires</h3>
        <button type="button" @click="loadHistory">Afficher l’historique</button>
        <ul v-if="history?.data">
          <li v-for="item in history.data" :key="item.id">
            {{ item.ancien_proprietaire }} → {{ item.nouveau_proprietaire }} ({{ item.transfere_le }})
          </li>
        </ul>
        <BasePagination
          v-if="history"
          :current-page="history.current_page"
          :last-page="history.last_page"
          @change="loadHistoryPage"
        />
      </div>
    </template>
  </section>
</template>

<style scoped>
.details { display: grid; grid-template-columns: minmax(150px, 1fr) 2fr; gap: 0.7rem 1rem; }
.details dt { color: var(--color-muted); }
.details dd { margin: 0; }
.card { margin-bottom: 1rem; }
.owner-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; }
.transfer { display: flex; gap: 0.5rem; margin: 0.75rem 0; }
.inline-form { display: flex; flex-wrap: wrap; align-items: end; gap: 0.75rem; margin: 0.75rem 0; }
.inline-form label { display: grid; gap: 0.3rem; }
.inline-form input, .inline-form select, .inline-form textarea { min-height: 2.4rem; padding: 0.4rem 0.6rem; }
</style>
