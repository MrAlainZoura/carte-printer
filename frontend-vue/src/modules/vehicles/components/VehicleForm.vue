<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  initialValues: { type: Object, default: () => ({}) },
  editing: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
})
const emit = defineEmits(['submit'])
const emptyOwner = () => ({
  type: 'Personne Physique',
  nom_complet: '',
  raison_sociale: '',
  adresse_physique: '',
  telephone: '',
  impot: '',
})
const form = reactive({
  marque: '', type: '', couleur: '', immatriculation: '', usage: '', etat: '',
  an_fabrication: '', an_circulation: '', moteur: '', puissance: '', chassis: '',
  cartinspection: '', carte_numero: '', creer_proprietaire: '0', propietaire_id: '',
  nouveau_proprietaire: emptyOwner(),
})

watch(() => props.initialValues, (values) => {
  const vehicle = values.vehicule || values
  Object.assign(form, {
    marque: vehicle.marque || '',
    type: vehicle.type || '',
    couleur: vehicle.couleur || '',
    immatriculation: vehicle.immatriculation || vehicle.plaque?.code || '',
    usage: vehicle.usage || '',
    etat: vehicle.etat || '',
    an_fabrication: vehicle.an_fabrication || '',
    an_circulation: vehicle.an_circulation || '',
    moteur: vehicle.moteur || '',
    puissance: vehicle.puissance || '',
    chassis: vehicle.chassis || '',
    cartinspection: vehicle.cartinspection || '',
    carte_numero: vehicle.carte_roses?.[0]?.numero || '',
    propietaire_id: vehicle.propietaire_id || values.proprietaires?.[0]?.id || '',
  })
}, { immediate: true })

function submit() {
  const payload = { ...form }
  if (props.editing) {
    delete payload.immatriculation
    delete payload.carte_numero
    delete payload.creer_proprietaire
    delete payload.propietaire_id
    delete payload.nouveau_proprietaire
  } else if (payload.creer_proprietaire === '0') {
    delete payload.nouveau_proprietaire
  } else {
    delete payload.propietaire_id
  }
  emit('submit', payload)
}
</script>

<template>
  <form class="vehicle-form" @submit.prevent="submit">
    <div class="form-grid">
      <label class="form-field"><span>Marque</span><input v-model.trim="form.marque" required /></label>
      <label class="form-field"><span>Type</span><input v-model.trim="form.type" required /></label>
      <label class="form-field"><span>Couleur</span><input v-model.trim="form.couleur" required /></label>
      <label v-if="!editing" class="form-field"><span>Immatriculation</span><input v-model.trim="form.immatriculation" required /></label>
      <label class="form-field"><span>Usage</span><input v-model.trim="form.usage" required /></label>
      <label class="form-field"><span>État</span><input v-model.trim="form.etat" /></label>
      <label class="form-field"><span>Année de fabrication</span><input v-model.trim="form.an_fabrication" /></label>
      <label class="form-field"><span>Année de circulation</span><input v-model.trim="form.an_circulation" /></label>
      <label class="form-field"><span>Moteur</span><input v-model.trim="form.moteur" /></label>
      <label class="form-field"><span>Puissance</span><input v-model.trim="form.puissance" /></label>
      <label class="form-field"><span>Châssis</span><input v-model.trim="form.chassis" /></label>
      <label class="form-field"><span>Carte d’inspection</span><input v-model.trim="form.cartinspection" /></label>
    </div>

    <template v-if="!editing">
      <label class="form-field"><span>Numéro de carte rose (facultatif)</span><input v-model.trim="form.carte_numero" /></label>
      <label class="form-field"><span>Propriétaire</span>
        <select v-model="form.creer_proprietaire">
          <option value="0">Choisir un propriétaire existant</option>
          <option value="1">Créer un propriétaire</option>
        </select>
      </label>
      <label v-if="form.creer_proprietaire === '0'" class="form-field"><span>Propriétaire existant</span>
        <select v-model="form.propietaire_id" required>
          <option v-for="owner in initialValues.proprietaires || []" :key="owner.id" :value="owner.id">
            {{ owner.nom_complet || owner.raison_sociale || `Propriétaire #${owner.id}` }}
          </option>
        </select>
      </label>
      <fieldset v-else class="form-grid">
        <legend>Nouveau propriétaire</legend>
        <label class="form-field"><span>Type</span>
          <select v-model="form.nouveau_proprietaire.type">
            <option>Personne Physique</option><option>Entreprise</option><option>Organisation</option><option>Autres</option>
          </select>
        </label>
        <label v-if="form.nouveau_proprietaire.type === 'Personne Physique'" class="form-field">
          <span>Nom complet</span><input v-model.trim="form.nouveau_proprietaire.nom_complet" required />
        </label>
        <label v-else class="form-field">
          <span>Raison sociale</span><input v-model.trim="form.nouveau_proprietaire.raison_sociale" required />
        </label>
        <label class="form-field"><span>Téléphone (+243…)</span><input v-model.trim="form.nouveau_proprietaire.telephone" required /></label>
        <label class="form-field"><span>Adresse</span><input v-model.trim="form.nouveau_proprietaire.adresse_physique" required /></label>
        <label v-if="form.nouveau_proprietaire.type !== 'Personne Physique'" class="form-field">
          <span>Numéro d’impôt</span><input v-model.trim="form.nouveau_proprietaire.impot" required />
        </label>
      </fieldset>
      <p class="muted">La création nécessite un stock disponible de plaques et cartes roses dans le poste sélectionné.</p>
    </template>
    <button type="submit" :disabled="loading">{{ loading ? 'Enregistrement…' : 'Enregistrer' }}</button>
  </form>
</template>

<style scoped>
.vehicle-form { display: grid; gap: 1rem; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
.form-field input, .form-field select { min-height: 2.6rem; padding: 0.5rem 0.7rem; }
fieldset { border: 1px solid var(--color-border); border-radius: 0.6rem; padding: 1rem; }
button { justify-self: start; min-height: 2.6rem; padding: 0.5rem 0.9rem; }
</style>
