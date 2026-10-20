<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    openapi: '3.0.0',
    info: new OA\Info(
        title: 'Carte Rose API',
        version: '1.0.0',
        description: 'API de gestion Carte Rose. Utilisez Authorization: Bearer <token> pour les routes protégées. Pour les routes liées à un poste, envoyez X-Post-Travail-Id: <id>. La vue globale est disponible aux administrateurs avec X-Post-Travail-Global: true.'
    ),
    servers: [new OA\Server(url: '/api', description: 'API Carte Rose')],
    security: [['bearerAuth' => []]]
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Jeton obtenu avec POST /login.'
)]
#[OA\PathItem(
    path: '/login',
    post: new OA\Post(
        operationId: 'login',
        tags: ['Authentification'],
        summary: 'Connexion et obtention d’un JWT',
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['identifiant', 'password'],
                properties: [
                    new OA\Property(property: 'identifiant', type: 'string', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Connexion réussie, jeton retourné.'),
            new OA\Response(response: 401, description: 'Identifiants incorrects.'),
            new OA\Response(response: 422, description: 'Données invalides.'),
        ]
    )
)]
#[OA\PathItem(path: '/logout', post: new OA\Post(operationId: 'logout', tags: ['Authentification'], summary: 'Invalider le JWT courant', responses: [new OA\Response(response: 200, description: 'Jeton invalidé.')]))]
#[OA\PathItem(path: '/refresh', post: new OA\Post(operationId: 'refreshToken', tags: ['Authentification'], summary: 'Renouveler le JWT courant', responses: [new OA\Response(response: 200, description: 'Nouveau jeton retourné.')]))]
#[OA\PathItem(path: '/me', get: new OA\Get(operationId: 'currentUser', tags: ['Authentification'], summary: 'Afficher l’utilisateur connecté', responses: [new OA\Response(response: 200, description: 'Utilisateur authentifié.')]))]
#[OA\PathItem(path: '/postes', get: new OA\Get(operationId: 'availablePosts', tags: ['Postes de travail'], summary: 'Lister les postes accessibles', responses: [new OA\Response(response: 200, description: 'Postes de travail et poste courant.')]))]
#[OA\PathItem(path: '/postes/select', post: new OA\Post(operationId: 'selectPost', tags: ['Postes de travail'], summary: 'Sélectionner un poste de travail', responses: [new OA\Response(response: 200, description: 'Poste sélectionné.')]))]
#[OA\PathItem(path: '/postes/global', post: new OA\Post(operationId: 'selectGlobalView', tags: ['Postes de travail'], summary: 'Activer la vue globale administrateur', responses: [new OA\Response(response: 200, description: 'Vue globale activée.'), new OA\Response(response: 403, description: 'Accès réservé aux administrateurs autorisés.')]))]
#[OA\PathItem(
    path: '/postes/{id}/open',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    post: new OA\Post(operationId: 'openPost', tags: ['Postes de travail'], summary: 'Ouvrir un poste de travail (administrateur)', responses: [new OA\Response(response: 200, description: 'Poste ouvert.'), new OA\Response(response: 404, description: 'Poste introuvable.')])
)]
#[OA\PathItem(path: '/initialisation/poste', get: new OA\Get(operationId: 'initialPostSetupStatus', tags: ['Postes de travail'], summary: 'Vérifier l’initialisation du premier poste', responses: [new OA\Response(response: 200, description: 'État de l’initialisation.')]), post: new OA\Post(operationId: 'createInitialPost', tags: ['Postes de travail'], summary: 'Créer le premier poste (administrateur)', responses: [new OA\Response(response: 201, description: 'Premier poste créé.'), new OA\Response(response: 409, description: 'Un poste existe déjà.')]))]
#[OA\PathItem(path: '/dashboard', get: new OA\Get(operationId: 'dashboard', tags: ['Tableau de bord'], summary: 'Charger les indicateurs du tableau de bord', responses: [new OA\Response(response: 200, description: 'Indicateurs du tableau de bord.')]))]
#[OA\PathItem(
    path: '/vehicules',
    get: new OA\Get(operationId: 'listVehicles', tags: ['Véhicules'], summary: 'Lister les véhicules', responses: [new OA\Response(response: 200, description: 'Liste paginée des véhicules.')]),
    post: new OA\Post(operationId: 'createVehicle', tags: ['Véhicules'], summary: 'Créer un véhicule avec sa plaque et sa carte rose', responses: [new OA\Response(response: 201, description: 'Véhicule créé.'), new OA\Response(response: 422, description: 'Données invalides ou stock insuffisant.')])
)]
#[OA\PathItem(
    path: '/vehicules/{id}',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    get: new OA\Get(operationId: 'showVehicle', tags: ['Véhicules'], summary: 'Afficher un véhicule', responses: [new OA\Response(response: 200, description: 'Détails du véhicule.'), new OA\Response(response: 404, description: 'Véhicule introuvable.')]),
    put: new OA\Put(operationId: 'updateVehicle', tags: ['Véhicules'], summary: 'Modifier un véhicule', responses: [new OA\Response(response: 200, description: 'Véhicule modifié.')]),
    delete: new OA\Delete(operationId: 'deleteVehicle', tags: ['Véhicules'], summary: 'Supprimer un véhicule', responses: [new OA\Response(response: 200, description: 'Véhicule supprimé.')])
)]
#[OA\PathItem(path: '/vehicules/create', get: new OA\Get(operationId: 'vehicleFormData', tags: ['Véhicules'], summary: 'Charger les données nécessaires à la création', responses: [new OA\Response(response: 200, description: 'Données de formulaire.')]))]
#[OA\PathItem(path: '/vehicules/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editVehicleData', tags: ['Véhicules'], summary: 'Charger un véhicule pour modification', responses: [new OA\Response(response: 200, description: 'Données du véhicule.')]))]
#[OA\PathItem(
    path: '/vehicules/{id}/proprietaire',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    get: new OA\Get(operationId: 'vehicleOwnerTransferData', tags: ['Véhicules'], summary: 'Charger les données de transfert de propriétaire', responses: [new OA\Response(response: 200, description: 'Véhicule et propriétaires disponibles.')]),
    post: new OA\Post(operationId: 'transferVehicleOwner', tags: ['Véhicules'], summary: 'Transférer le véhicule à un autre propriétaire', responses: [new OA\Response(response: 200, description: 'Propriétaire transféré.'), new OA\Response(response: 422, description: 'Données invalides.')])
)]
#[OA\PathItem(
    path: '/vehicules/{id}/historique-proprietaires',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    get: new OA\Get(operationId: 'vehicleOwnerHistory', tags: ['Véhicules'], summary: 'Afficher l’historique des propriétaires', responses: [new OA\Response(response: 200, description: 'Historique paginé.')])
)]
#[OA\PathItem(
    path: '/vehicules/{id}/plaque',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    get: new OA\Get(operationId: 'vehiclePlaqueChangeData', tags: ['Véhicules'], summary: 'Charger les données de remplacement de plaque', responses: [new OA\Response(response: 200, description: 'Données du véhicule.')]),
    post: new OA\Post(operationId: 'changeVehiclePlaque', tags: ['Véhicules'], summary: 'Remplacer la plaque du véhicule', responses: [new OA\Response(response: 200, description: 'Plaque remplacée.'), new OA\Response(response: 422, description: 'Données invalides ou stock insuffisant.')])
)]
#[OA\PathItem(path: '/vehicules/{vehicule}/plaque/{plaque}/edit', parameters: [new OA\Parameter(name: 'vehicule', in: 'path', required: true, schema: new OA\Schema(type: 'integer')), new OA\Parameter(name: 'plaque', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editPlaque', tags: ['Plaques'], summary: 'Charger une plaque pour modification', responses: [new OA\Response(response: 200, description: 'Données de la plaque.')]))]
#[OA\PathItem(path: '/vehicules/{vehicule}/plaque/{plaque}', parameters: [new OA\Parameter(name: 'vehicule', in: 'path', required: true, schema: new OA\Schema(type: 'integer')), new OA\Parameter(name: 'plaque', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], put: new OA\Put(operationId: 'updatePlaque', tags: ['Plaques'], summary: 'Modifier une plaque', responses: [new OA\Response(response: 200, description: 'Plaque modifiée.')]))]
#[OA\PathItem(path: '/plaques/{plaque}', parameters: [new OA\Parameter(name: 'plaque', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showPlaque', tags: ['Plaques'], summary: 'Afficher une plaque', responses: [new OA\Response(response: 200, description: 'Plaque chargée.')]))]
#[OA\PathItem(path: '/vehicules/{vehicule}/carte-rose/{carteRose}/edit', parameters: [new OA\Parameter(name: 'vehicule', in: 'path', required: true, schema: new OA\Schema(type: 'integer')), new OA\Parameter(name: 'carteRose', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editCarteRose', tags: ['Cartes roses'], summary: 'Charger une carte rose pour modification', responses: [new OA\Response(response: 200, description: 'Données de la carte rose.')]))]
#[OA\PathItem(path: '/vehicules/{vehicule}/carte-rose/{carteRose}', parameters: [new OA\Parameter(name: 'vehicule', in: 'path', required: true, schema: new OA\Schema(type: 'integer')), new OA\Parameter(name: 'carteRose', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], put: new OA\Put(operationId: 'updateCarteRose', tags: ['Cartes roses'], summary: 'Modifier une carte rose', responses: [new OA\Response(response: 200, description: 'Carte rose modifiée.')]))]
#[OA\PathItem(path: '/carte/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'printCarteRose', tags: ['Cartes roses'], summary: 'Générer le PDF de la carte rose', responses: [new OA\Response(response: 200, description: 'Document PDF.', content: new OA\MediaType(mediaType: 'application/pdf')), new OA\Response(response: 404, description: 'Véhicule introuvable.')]))]
#[OA\PathItem(path: '/post-travails', get: new OA\Get(operationId: 'listWorkPosts', tags: ['Postes de travail'], summary: 'Lister les postes de travail', responses: [new OA\Response(response: 200, description: 'Liste paginée des postes.')]), post: new OA\Post(operationId: 'createWorkPost', tags: ['Postes de travail'], summary: 'Créer un poste de travail', responses: [new OA\Response(response: 201, description: 'Poste créé.')]))]
#[OA\PathItem(path: '/post-travails/create', get: new OA\Get(operationId: 'workPostFormData', tags: ['Postes de travail'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/post-travails/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showWorkPost', tags: ['Postes de travail'], summary: 'Afficher un poste de travail', responses: [new OA\Response(response: 200, description: 'Poste chargé.')]), put: new OA\Put(operationId: 'updateWorkPost', tags: ['Postes de travail'], summary: 'Modifier un poste de travail', responses: [new OA\Response(response: 200, description: 'Poste modifié.')]), patch: new OA\Patch(operationId: 'patchWorkPost', tags: ['Postes de travail'], summary: 'Modifier un poste de travail', responses: [new OA\Response(response: 200, description: 'Poste modifié.')]), delete: new OA\Delete(operationId: 'deleteWorkPost', tags: ['Postes de travail'], summary: 'Supprimer un poste', responses: [new OA\Response(response: 200, description: 'Poste supprimé.')]))]
#[OA\PathItem(path: '/post-travails/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editWorkPostData', tags: ['Postes de travail'], summary: 'Charger un poste pour modification', responses: [new OA\Response(response: 200, description: 'Données du poste.')]))]
#[OA\PathItem(path: '/post-produits', get: new OA\Get(operationId: 'listPostProducts', tags: ['Produits des postes'], summary: 'Lister les produits des postes', responses: [new OA\Response(response: 200, description: 'Liste paginée.')]), post: new OA\Post(operationId: 'createPostProduct', tags: ['Produits des postes'], summary: 'Affecter un produit à un poste', responses: [new OA\Response(response: 201, description: 'Affectation créée.')]))]
#[OA\PathItem(path: '/post-produits/create', get: new OA\Get(operationId: 'postProductFormData', tags: ['Produits des postes'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/post-produits/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showPostProduct', tags: ['Produits des postes'], summary: 'Afficher une affectation', responses: [new OA\Response(response: 200, description: 'Affectation chargée.')]), put: new OA\Put(operationId: 'updatePostProduct', tags: ['Produits des postes'], summary: 'Modifier une affectation', responses: [new OA\Response(response: 200, description: 'Affectation modifiée.')]), patch: new OA\Patch(operationId: 'patchPostProduct', tags: ['Produits des postes'], summary: 'Modifier une affectation', responses: [new OA\Response(response: 200, description: 'Affectation modifiée.')]), delete: new OA\Delete(operationId: 'deletePostProduct', tags: ['Produits des postes'], summary: 'Supprimer une affectation', responses: [new OA\Response(response: 200, description: 'Affectation supprimée.')]))]
#[OA\PathItem(path: '/post-produits/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editPostProductData', tags: ['Produits des postes'], summary: 'Charger une affectation pour modification', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/approvisionnements', get: new OA\Get(operationId: 'listSupplies', tags: ['Approvisionnements'], summary: 'Lister les approvisionnements', responses: [new OA\Response(response: 200, description: 'Liste paginée.')]), post: new OA\Post(operationId: 'createSupply', tags: ['Approvisionnements'], summary: 'Créer un approvisionnement', responses: [new OA\Response(response: 201, description: 'Approvisionnement créé.')]))]
#[OA\PathItem(path: '/approvisionnements/create', get: new OA\Get(operationId: 'supplyFormData', tags: ['Approvisionnements'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/approvisionnements/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showSupply', tags: ['Approvisionnements'], summary: 'Afficher un approvisionnement', responses: [new OA\Response(response: 200, description: 'Approvisionnement chargé.')]), put: new OA\Put(operationId: 'updateSupply', tags: ['Approvisionnements'], summary: 'Modifier un approvisionnement', responses: [new OA\Response(response: 200, description: 'Approvisionnement modifié.')]), delete: new OA\Delete(operationId: 'deleteSupply', tags: ['Approvisionnements'], summary: 'Supprimer un approvisionnement', responses: [new OA\Response(response: 200, description: 'Approvisionnement supprimé.')]))]
#[OA\PathItem(path: '/approvisionnements/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editSupplyData', tags: ['Approvisionnements'], summary: 'Charger un approvisionnement pour modification', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/approvisionnements/{id}/confirm', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], patch: new OA\Patch(operationId: 'confirmSupply', tags: ['Approvisionnements'], summary: 'Confirmer la réception d’un approvisionnement', responses: [new OA\Response(response: 200, description: 'Réception confirmée.'), new OA\Response(response: 422, description: 'Approvisionnement déjà confirmé.')]))]
#[OA\PathItem(path: '/produits', get: new OA\Get(operationId: 'listProducts', tags: ['Produits'], summary: 'Lister les produits', responses: [new OA\Response(response: 200, description: 'Produits et catégories.')]), post: new OA\Post(operationId: 'createProduct', tags: ['Produits'], summary: 'Créer un produit', responses: [new OA\Response(response: 201, description: 'Produit créé.')]))]
#[OA\PathItem(path: '/produits/create', get: new OA\Get(operationId: 'productFormData', tags: ['Produits'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/produits/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showProduct', tags: ['Produits'], summary: 'Afficher un produit', responses: [new OA\Response(response: 200, description: 'Produit chargé.')]), put: new OA\Put(operationId: 'updateProduct', tags: ['Produits'], summary: 'Modifier un produit', responses: [new OA\Response(response: 200, description: 'Produit modifié.')]), patch: new OA\Patch(operationId: 'patchProduct', tags: ['Produits'], summary: 'Modifier un produit', responses: [new OA\Response(response: 200, description: 'Produit modifié.')]), delete: new OA\Delete(operationId: 'deleteProduct', tags: ['Produits'], summary: 'Supprimer un produit', responses: [new OA\Response(response: 200, description: 'Produit supprimé.')]))]
#[OA\PathItem(path: '/produits/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editProductData', tags: ['Produits'], summary: 'Charger un produit pour modification', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/proprietaires', get: new OA\Get(operationId: 'listOwners', tags: ['Propriétaires'], summary: 'Lister les propriétaires', responses: [new OA\Response(response: 200, description: 'Liste paginée.')]), post: new OA\Post(operationId: 'createOwner', tags: ['Propriétaires'], summary: 'Créer un propriétaire', responses: [new OA\Response(response: 201, description: 'Propriétaire créé.')]))]
#[OA\PathItem(path: '/proprietaires/create', get: new OA\Get(operationId: 'ownerFormData', tags: ['Propriétaires'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/proprietaires/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showOwner', tags: ['Propriétaires'], summary: 'Afficher un propriétaire', responses: [new OA\Response(response: 200, description: 'Propriétaire chargé.')]), put: new OA\Put(operationId: 'updateOwner', tags: ['Propriétaires'], summary: 'Modifier un propriétaire', responses: [new OA\Response(response: 200, description: 'Propriétaire modifié.')]), patch: new OA\Patch(operationId: 'patchOwner', tags: ['Propriétaires'], summary: 'Modifier un propriétaire', responses: [new OA\Response(response: 200, description: 'Propriétaire modifié.')]), delete: new OA\Delete(operationId: 'deleteOwner', tags: ['Propriétaires'], summary: 'Supprimer un propriétaire', responses: [new OA\Response(response: 200, description: 'Propriétaire supprimé.')]))]
#[OA\PathItem(path: '/proprietaires/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editOwnerData', tags: ['Propriétaires'], summary: 'Charger un propriétaire pour modification', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/users/profile', get: new OA\Get(operationId: 'userProfile', tags: ['Utilisateurs'], summary: 'Afficher le profil connecté', responses: [new OA\Response(response: 200, description: 'Profil et statistiques.')]))]
#[OA\PathItem(path: '/users/password', put: new OA\Put(operationId: 'updatePassword', tags: ['Utilisateurs'], summary: 'Modifier le mot de passe courant', responses: [new OA\Response(response: 200, description: 'Mot de passe modifié.'), new OA\Response(response: 422, description: 'Mot de passe actuel ou données invalides.')]))]
#[OA\PathItem(path: '/users', get: new OA\Get(operationId: 'listUsers', tags: ['Utilisateurs'], summary: 'Lister les utilisateurs (administrateur)', responses: [new OA\Response(response: 200, description: 'Liste paginée.')]), post: new OA\Post(operationId: 'createUser', tags: ['Utilisateurs'], summary: 'Créer un utilisateur', responses: [new OA\Response(response: 201, description: 'Utilisateur créé.')]))]
#[OA\PathItem(path: '/users/create', get: new OA\Get(operationId: 'userFormData', tags: ['Utilisateurs'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/users/{id}', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'showUser', tags: ['Utilisateurs'], summary: 'Afficher un utilisateur', responses: [new OA\Response(response: 200, description: 'Utilisateur et statistiques.')]), put: new OA\Put(operationId: 'updateUser', tags: ['Utilisateurs'], summary: 'Modifier un utilisateur', responses: [new OA\Response(response: 200, description: 'Utilisateur modifié.')]), patch: new OA\Patch(operationId: 'patchUser', tags: ['Utilisateurs'], summary: 'Modifier un utilisateur', responses: [new OA\Response(response: 200, description: 'Utilisateur modifié.')]), delete: new OA\Delete(operationId: 'deleteUser', tags: ['Utilisateurs'], summary: 'Supprimer un utilisateur', responses: [new OA\Response(response: 200, description: 'Utilisateur supprimé.')]))]
#[OA\PathItem(path: '/users/{id}/edit', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], get: new OA\Get(operationId: 'editUserData', tags: ['Utilisateurs'], summary: 'Charger un utilisateur pour modification', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/users/{id}/reset-password', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], post: new OA\Post(operationId: 'resetUserPassword', tags: ['Utilisateurs'], summary: 'Réinitialiser le mot de passe (administrateur)', responses: [new OA\Response(response: 200, description: 'Mot de passe temporaire retourné.')]))]
#[OA\PathItem(path: '/roles', get: new OA\Get(operationId: 'listRoles', tags: ['Rôles'], summary: 'Lister les rôles', responses: [new OA\Response(response: 200, description: 'Rôles et permissions.')]), post: new OA\Post(operationId: 'createRole', tags: ['Rôles'], summary: 'Créer un rôle', responses: [new OA\Response(response: 201, description: 'Rôle créé.')]))]
#[OA\PathItem(path: '/roles/create', get: new OA\Get(operationId: 'roleFormData', tags: ['Rôles'], summary: 'Charger les données de création', responses: [new OA\Response(response: 200, description: 'Données du formulaire.')]))]
#[OA\PathItem(path: '/roles/{role}', parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string'))], get: new OA\Get(operationId: 'showRole', tags: ['Rôles'], summary: 'Afficher un rôle', responses: [new OA\Response(response: 200, description: 'Rôle et permissions.')]), put: new OA\Put(operationId: 'updateRole', tags: ['Rôles'], summary: 'Modifier un rôle', responses: [new OA\Response(response: 200, description: 'Rôle modifié.')]), delete: new OA\Delete(operationId: 'deleteRole', tags: ['Rôles'], summary: 'Supprimer un rôle', responses: [new OA\Response(response: 200, description: 'Rôle supprimé.')]))]
#[OA\PathItem(path: '/roles/{role}/edit', parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string'))], get: new OA\Get(operationId: 'editRoleData', tags: ['Rôles'], summary: 'Charger un rôle pour modification', responses: [new OA\Response(response: 200, description: 'Données du rôle.')]))]
#[OA\PathItem(path: '/roles/{role}/permissions', parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string'))], put: new OA\Put(operationId: 'syncRolePermissions', tags: ['Rôles'], summary: 'Remplacer les permissions d’un rôle', responses: [new OA\Response(response: 200, description: 'Permissions mises à jour.')]))]
class ApiDocumentation
{
}
