import { ref } from 'vue'

const message = ref('')
const type = ref('success')

export function useNotification() {
  function notify(text, variant = 'success') {
    message.value = text
    type.value = variant
    window.setTimeout(() => {
      if (message.value === text) message.value = ''
    }, 3500)
  }

  return { message, type, notify }
}
