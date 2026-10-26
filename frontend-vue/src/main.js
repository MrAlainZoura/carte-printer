import { createApp, nextTick } from 'vue'
import { createPinia } from 'pinia'
import { initFlowbite } from 'flowbite'
import App from './App.vue'
import router from './router'
import './assets/styles/tailwind.css'
import './assets/styles/main.css'

router.afterEach(() => {
  nextTick(() => initFlowbite())
})

createApp(App)
  .use(createPinia())
  .use(router)
  .mount('#app')
