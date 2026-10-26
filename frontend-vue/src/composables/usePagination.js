import { ref } from 'vue'

export function usePagination() {
  const page = ref(1)
  const lastPage = ref(1)

  function update(paginator) {
    page.value = Number(paginator?.current_page || 1)
    lastPage.value = Number(paginator?.last_page || 1)
  }

  return { page, lastPage, update }
}
