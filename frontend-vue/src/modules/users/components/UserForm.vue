<script setup>
import { reactive, watch } from 'vue'
import BaseButton from '@/components/common/BaseButton.vue'
import BaseInput from '@/components/common/BaseInput.vue'

const props = defineProps({
  initialValues: { type: Object, default: () => ({}) },
  editing: { type: Boolean, default: false },
  posts: { type: Array, default: () => [] },
  roles: { type: Object, default: () => ({}) },
  loading: { type: Boolean, default: false },
})
const emit = defineEmits(['submit'])
const form = reactive({ name: '', email: '', tel: '', password: '', password_confirmation: '', post_travail_id: '', role: '' })

watch(() => props.initialValues, (values) => {
  Object.assign(form, {
    name: values.record?.name || '',
    email: values.record?.email || '',
    tel: values.record?.tel || '',
    post_travail_id: values.record?.post_travails?.[0]?.id || props.posts[0]?.id || '',
    role: values.record?.roles?.[0]?.name || Object.keys(props.roles)[0] || '',
    password: '',
    password_confirmation: '',
  })
}, { immediate: true })
</script>

<template>
  <form class="stack" @submit.prevent="emit('submit', { ...form })">
    <BaseInput v-model="form.name" name="name" label="Nom" required />
    <BaseInput v-model="form.email" name="email" label="Email" type="email" />
    <BaseInput v-model="form.tel" name="tel" label="Téléphone" />
    <template v-if="!editing">
      <BaseInput v-model="form.password" name="password" label="Mot de passe" type="password" required />
      <BaseInput v-model="form.password_confirmation" name="password_confirmation" label="Confirmer le mot de passe" type="password" required />
    </template>
    <label v-if="posts.length" class="form-field" for="post_travail_id">
      <span>Poste de travail</span>
      <select id="post_travail_id" v-model="form.post_travail_id" required>
        <option v-for="post in posts" :key="post.id" :value="post.id">{{ post.libele }}</option>
      </select>
    </label>
    <label v-if="Object.keys(roles).length" class="form-field" for="role">
      <span>Rôle</span>
      <select id="role" v-model="form.role" required>
        <option v-for="(label, role) in roles" :key="role" :value="role">{{ label }}</option>
      </select>
    </label>
    <BaseButton type="submit" :disabled="loading">{{ loading ? 'Enregistrement…' : 'Enregistrer' }}</BaseButton>
  </form>
</template>
