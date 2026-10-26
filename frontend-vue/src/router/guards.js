export function authGuard(to) {
  const authenticated = Boolean(sessionStorage.getItem('carterose.jwt'))
  if (to.meta.requiresAuth && !authenticated) return { name: 'login' }
  if (to.meta.guestOnly && authenticated) return { name: 'dashboard' }
  return true
}
