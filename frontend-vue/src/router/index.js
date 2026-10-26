import { createRouter, createWebHistory } from 'vue-router'
import { authGuard } from './guards'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      component: AuthLayout,
      meta: { guestOnly: true },
      children: [{ path: '', name: 'login', component: () => import('@/modules/auth/views/LoginView.vue') }],
    },
    {
      path: '/register',
      component: AuthLayout,
      meta: { guestOnly: true },
      children: [{ path: '', name: 'register', component: () => import('@/modules/auth/views/RegisterView.vue') }],
    },
    {
      path: '/',
      component: DefaultLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '', redirect: { name: 'dashboard' } },
        { path: 'dashboard', name: 'dashboard', component: () => import('@/modules/dashboard/views/DashboardView.vue') },
        { path: 'users', name: 'users.index', component: () => import('@/modules/users/views/UserListView.vue') },
        { path: 'users/create', name: 'users.create', component: () => import('@/modules/users/views/UserCreateView.vue') },
        { path: 'users/:id', name: 'users.show', component: () => import('@/modules/users/views/UserDetailView.vue'), props: true },
        { path: 'users/:id/edit', name: 'users.edit', component: () => import('@/modules/users/views/UserEditView.vue'), props: true },
        { path: 'roles', name: 'roles.index', component: () => import('@/modules/roles/views/RoleListView.vue') },
        { path: 'roles/create', name: 'roles.create', component: () => import('@/modules/roles/views/RoleFormView.vue') },
        { path: 'roles/:id', name: 'roles.show', component: () => import('@/modules/roles/views/RoleDetailView.vue') },
        { path: 'roles/:id/edit', name: 'roles.edit', component: () => import('@/modules/roles/views/RoleFormView.vue') },
        { path: 'products', name: 'products.index', component: () => import('@/modules/products/views/ProductListView.vue') },
        { path: 'products/create', name: 'products.create', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'products' } },
        { path: 'products/:id', name: 'products.show', component: () => import('@/modules/resources/views/ResourceDetailView.vue'), meta: { resource: 'products' } },
        { path: 'products/:id/edit', name: 'products.edit', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'products' } },
        { path: 'owners', name: 'owners.index', component: () => import('@/modules/resources/views/ResourceListView.vue'), meta: { resource: 'owners' } },
        { path: 'owners/create', name: 'owners.create', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'owners' } },
        { path: 'owners/:id', name: 'owners.show', component: () => import('@/modules/resources/views/ResourceDetailView.vue'), meta: { resource: 'owners' } },
        { path: 'owners/:id/edit', name: 'owners.edit', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'owners' } },
        { path: 'posts', name: 'posts.index', component: () => import('@/modules/resources/views/ResourceListView.vue'), meta: { resource: 'posts' } },
        { path: 'posts/create', name: 'posts.create', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'posts' } },
        { path: 'posts/:id', name: 'posts.show', component: () => import('@/modules/resources/views/ResourceDetailView.vue'), meta: { resource: 'posts' } },
        { path: 'posts/:id/edit', name: 'posts.edit', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'posts' } },
        { path: 'stock', name: 'stock.index', component: () => import('@/modules/resources/views/ResourceListView.vue'), meta: { resource: 'stock' } },
        { path: 'stock/create', name: 'stock.create', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'stock' } },
        { path: 'stock/:id', name: 'stock.show', component: () => import('@/modules/resources/views/ResourceDetailView.vue'), meta: { resource: 'stock' } },
        { path: 'stock/:id/edit', name: 'stock.edit', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'stock' } },
        { path: 'supplies', name: 'supplies.index', component: () => import('@/modules/resources/views/ResourceListView.vue'), meta: { resource: 'supplies' } },
        { path: 'supplies/create', name: 'supplies.create', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'supplies' } },
        { path: 'supplies/:id', name: 'supplies.show', component: () => import('@/modules/resources/views/ResourceDetailView.vue'), meta: { resource: 'supplies' } },
        { path: 'supplies/:id/edit', name: 'supplies.edit', component: () => import('@/modules/resources/views/ResourceFormView.vue'), meta: { resource: 'supplies' } },
        { path: 'profile', name: 'profile', component: () => import('@/modules/profile/views/ProfileView.vue') },
        { path: 'vehicles', name: 'vehicles.index', component: () => import('@/modules/vehicles/views/VehicleListView.vue') },
        { path: 'vehicles/create', name: 'vehicles.create', component: () => import('@/modules/vehicles/views/VehicleFormView.vue') },
        { path: 'vehicles/:id', name: 'vehicles.show', component: () => import('@/modules/vehicles/views/VehicleDetailView.vue') },
        { path: 'vehicles/:id/edit', name: 'vehicles.edit', component: () => import('@/modules/vehicles/views/VehicleFormView.vue') },
      ],
    },
    { path: '/:pathMatch(.*)*', redirect: { name: 'dashboard' } },
  ],
})

router.beforeEach(authGuard)

export default router
