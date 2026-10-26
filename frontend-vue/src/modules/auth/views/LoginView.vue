<script setup>
import LoginForm from '../components/LoginForm.vue'
</script>

<template>
  <section class="card auth-card stack">
    <div>
      <p class="eyebrow">ESPACE DE GESTION</p>
      <h1>Connexion à CarteRose</h1>
      <p class="muted">Connectez-vous avec votre compte de travail.</p>
    </div>
    <LoginForm />
    <p class="register-link">Vous n’avez pas encore de compte ? <RouterLink to="/register">Créer un compte</RouterLink></p>
  </section>
</template>

<style scoped>
.eyebrow {
  color: var(--color-primary);
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.1em;
}

h1 { margin-bottom: 0.4rem; }
.register-link { margin: 0; text-align: center; color: var(--color-muted); }
.register-link a { color: var(--color-primary); font-weight: 700; }
</style>
