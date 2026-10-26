import { createResourceService } from '@/services/resource-service'

const ownerService = createResourceService({ endpoint: '/proprietaires', listKey: 'proprietaires' })
const productService = createResourceService({ endpoint: '/produits', listKey: 'produits' })
const postService = createResourceService({ endpoint: '/post-travails', listKey: 'postTravails' })
const stockService = createResourceService({ endpoint: '/post-produits', listKey: 'postProduits' })
const supplyService = createResourceService({ endpoint: '/approvisionnements', listKey: 'approvisionnements' })

export const resources = {
  owners: {
    title: 'Propriétaires',
    description: 'Coordonnées des propriétaires de véhicules.',
    service: ownerService,
    showKey: 'record',
    columns: [
      { key: 'nom_complet', label: 'Nom' },
      { key: 'raison_sociale', label: 'Raison sociale' },
      { key: 'type', label: 'Type' },
      { key: 'telephone', label: 'Téléphone' },
      { key: 'vehicules_count', label: 'Véhicules' },
    ],
    fields: [
      { key: 'type', label: 'Type', type: 'select', required: true, options: ['Personne Physique', 'Entreprise', 'Organisation', 'Autres'] },
      { key: 'nom_complet', label: 'Nom complet' },
      { key: 'raison_sociale', label: 'Raison sociale' },
      { key: 'adresse_physique', label: 'Adresse', type: 'textarea' },
      { key: 'telephone', label: 'Téléphone' },
      { key: 'impot', label: 'Numéro d’impôt' },
    ],
  },
  products: {
    title: 'Produits',
    description: 'Catalogue des produits et fournitures.',
    service: productService,
    showKey: 'produit',
    columns: [
      { key: 'libele', label: 'Libellé' },
      { key: 'categorie', label: 'Catégorie' },
      { key: 'marque', label: 'Marque' },
      { key: 'unite', label: 'Unité' },
      { key: 'etat', label: 'État' },
    ],
    fields: [
      { key: 'libele', label: 'Libellé', required: true },
      { key: 'description', label: 'Description', type: 'textarea' },
      { key: 'marque', label: 'Marque' },
      { key: 'categorie', label: 'Catégorie' },
      { key: 'unite', label: 'Unité' },
      { key: 'etat', label: 'État' },
    ],
  },
  posts: {
    title: 'Postes de travail',
    description: 'Gestion des sites, coordonnées et accès des agents.',
    service: postService,
    showKey: 'record',
    columns: [
      { key: 'libele', label: 'Poste' },
      { key: 'ville', label: 'Ville' },
      { key: 'email', label: 'Email' },
      { key: 'users_count', label: 'Agents' },
      { key: 'actif', label: 'État', format: (value) => value ? 'Actif' : 'Inactif' },
    ],
    fields: [
      { key: 'libele', label: 'Nom du poste', required: true },
      { key: 'email', label: 'Email', type: 'email' },
      { key: 'contact1', label: 'Contact principal' },
      { key: 'contact', label: 'Contact secondaire' },
      { key: 'cpostal', label: 'Code postal' },
      { key: 'pays', label: 'Pays' },
      { key: 'province', label: 'Province' },
      { key: 'ville', label: 'Ville' },
      { key: 'avenue', label: 'Avenue' },
      { key: 'autres', label: 'Autres informations', type: 'textarea' },
      { key: 'lon', label: 'Longitude', type: 'number' },
      { key: 'lat', label: 'Latitude', type: 'number' },
      { key: 'use_cdf', label: 'Utiliser le CDF', type: 'checkbox' },
      { key: 'actif', label: 'Poste actif', type: 'checkbox' },
      { key: 'user_ids', label: 'Agents rattachés', type: 'multiselect', optionsKey: 'users', optionLabel: 'name' },
    ],
    normalizeForm: (data) => ({
      ...data,
      record: {
        ...data.record,
        use_cdf: data.record?.use_cdf ?? true,
        actif: data.record?.actif ?? true,
        user_ids: (data.record?.users || []).map((user) => user.id),
      },
    }),
  },
  stock: {
    title: 'Produits des postes',
    description: 'Quantités et prix des produits associés au poste actif.',
    service: stockService,
    showKey: 'record',
    columns: [
      { key: 'produit.libele', label: 'Produit' },
      { key: 'post_travail.libele', label: 'Poste' },
      { key: 'quantite', label: 'Quantité' },
      { key: 'prix', label: 'Prix' },
    ],
    fields: [
      { key: 'produit_id', label: 'Produit', type: 'selectMap', optionsKey: 'produits', required: true },
      { key: 'post_travail_id', label: 'Poste', type: 'selectMap', optionsKey: 'postTravails', required: true },
      { key: 'quantite', label: 'Quantité', type: 'number', required: true },
      { key: 'prix', label: 'Prix', type: 'number' },
    ],
  },
  supplies: {
    title: 'Approvisionnements',
    description: 'Entrées de produits et confirmations de réception.',
    service: supplyService,
    showKey: 'approvisionnement',
    columns: [
      { key: 'produit.libele', label: 'Produit' },
      { key: 'post_travail.libele', label: 'Poste' },
      { key: 'quantite', label: 'Quantité' },
      { key: 'origine', label: 'Origine' },
      { key: 'confirm', label: 'Réception', format: (value) => value ? 'Confirmée' : 'En attente' },
    ],
    fields: [
      { key: 'posttravail_id', label: 'Poste', type: 'selectList', optionsKey: 'posts', optionLabel: 'libele', required: true },
      { key: 'produit_id', label: 'Produit', type: 'selectList', optionsKey: 'produits', optionLabel: 'libele', required: true },
      { key: 'quantite', label: 'Quantité', type: 'number', required: true },
      { key: 'origine', label: 'Origine' },
    ],
  },
}
