# CarteRose

CarteRose est une application Laravel de gestion des véhicules, propriétaires,
plaques d’immatriculation, cartes roses et stocks par poste de travail. Elle
propose une interface web Blade et une API JSON sécurisée par JWT. Les deux
interfaces utilisent les mêmes services métier ; l’authentification API reste
distincte de l’authentification web par session.

## Fonctionnalités

- Gestion des véhicules, des propriétaires, des cartes roses et des plaques.
- Historique du propriétaire initial dès l’enregistrement du véhicule et transferts
  vers un propriétaire existant ou nouvellement créé, conservés dans
  `historique_proprietaires_vehicules` (ancien/nouveau propriétaire, date et utilisateur).
- Gestion des postes de travail, des produits, des stocks et des approvisionnements.
- Gestion des utilisateurs, des rôles et des permissions.
- Génération et impression PDF des cartes roses avec QR code.
- API JSON avec permissions et sélection du poste de travail.
- Documentation interactive de l’API avec Swagger UI et OpenAPI.

## Installation et lancement

Prérequis : PHP 8.3+, Composer, Node.js/npm et une base de données configurée.

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configurez DB_* dans .env, puis :
php artisan migrate --seed
npm install
composer run dev
```

Pour exécuter uniquement le serveur Laravel :

```bash
php artisan serve
```

## Application Vue

Le frontend Vue indépendant se trouve dans `frontend-vue/`. Il utilise Vue 3,
Pinia, Tailwind CSS et Flowbite, et communique avec l’API Laravel via JWT. Il
propose notamment le tableau de bord Blade-like, les véhicules et leurs
propriétaires, les produits du catalogue et des postes, les approvisionnements,
les postes de travail, les utilisateurs, les rôles et le profil utilisateur.
La navigation Vue tient compte des permissions renvoyées par l’API, qui restent
également contrôlées côté serveur.
Si le lien « Changer de propriétaire » n’apparaît pas pour un compte existant,
attribuez les permissions mises à jour avec
`php artisan db:seed --class=UpdateVehiculeTransferPermissionSeeder`.
Après avoir lancé Laravel sur le port 8000 :

```bash
cd frontend-vue
npm install
cp .env.example .env
npm run dev
```

L’interface est disponible sur `http://localhost:5173`. Si nécessaire, modifiez
`VITE_API_BASE_URL` dans `frontend-vue/.env` pour pointer vers l’API.

## API et authentification

Les endpoints API sont préfixés par `/api`. Commencez par obtenir un jeton :

```http
POST /api/login
Content-Type: application/json

{
  "identifiant": "admin@example.com",
  "password": "votre-mot-de-passe"
}
```

Envoyez ensuite le jeton retourné aux endpoints protégés :

```http
Authorization: Bearer <token>
Accept: application/json
```

L’inscription publique utilise `GET /api/register/options` pour récupérer les
postes actifs et les rôles non-administrateur, puis `POST /api/register` avec le
nom, l’email, le téléphone, le poste, le rôle, le mot de passe et sa confirmation.
L’inscription est limitée en fréquence, fermée selon la même règle que le web,
et ne permet jamais de créer un administrateur. Après création, l’utilisateur
se connecte via `POST /api/login`.

Les autres routes d’authentification sont `POST /api/login`, `POST /api/logout`,
`POST /api/refresh` et `GET /api/me`. L’API métier couvre notamment les
véhicules, propriétaires, plaques, cartes roses, postes de travail, produits,
stocks, approvisionnements, utilisateurs, rôles et le tableau de bord.

Pour les opérations liées à un poste, le middleware sélectionne par défaut le
poste de travail par défaut de l’utilisateur. Pour cibler un autre poste
accessible à l’utilisateur, ajoutez :

```http
X-Post-Travail-Id: 12
```

Les administrateurs autorisés peuvent demander la vue globale avec :

```http
X-Post-Travail-Global: true
```

La sélection explicite d’un poste et la vue globale sont des contextes par
requête ; l’API ne dépend pas d’une session web. Les permissions et rôles sont
également vérifiés sur les endpoints API.

## Swagger, OpenAPI et OpenAI

- **Swagger UI** est l’interface web interactive pour parcourir et essayer les
  endpoints documentés : `/api/documentation`.
- **OpenAPI** est le format normalisé de la spécification qui décrit les routes,
  méthodes HTTP, paramètres, réponses et sécurité JWT. Cette spécification est
  générée depuis `app/OpenApi/ApiDocumentation.php` et servie au format JSON par
  `/docs?api-docs.json`.
- **OpenAI n’est pas utilisé** dans ce projet. OpenAPI n’est pas un modèle
  d’intelligence artificielle : c’est uniquement le format de description de
  l’API.

Après avoir modifié les annotations OpenAPI, régénérez le document avec :

```bash
php artisan l5-swagger:generate
```

Les routes Swagger et le document sont publics par défaut ; ne les exposez pas
si votre déploiement exige une documentation privée.

## Architecture

- `routes/web.php` : interface web et routes avec authentification par session.
- `routes/api.php` : endpoints JSON et authentification API JWT.
- `app/Http/Controllers/Api/` : contrôleurs API fins qui réutilisent les services.
- `app/Services/` : logique métier commune au web et à l’API.
- `app/OpenApi/ApiDocumentation.php` : définition de la documentation OpenAPI.
- `app/Support/CurrentPostTravail.php` : contexte de poste pour les requêtes.

Le détail du domaine et les consignes de développement sont dans
[`DOCUMENTATION_DEV.txt`](DOCUMENTATION_DEV.txt).
