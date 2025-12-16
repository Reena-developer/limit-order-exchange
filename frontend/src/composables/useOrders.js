import { ref } from 'vue'
import { fetchOrders } from '@/api/orders.api'



export function useOrders() {
  const rows = ref([])
  const loading = ref(false)

  const params = ref({
    symbol: 'all',
    side: 'all',
    status: 'all',
    sortBy: 'id',
    sortDir: 'desc',
    page: 1,
    perPage: 20,
  })

  async function getOrders() {
    loading.value = true
    try {
      rows.value = await fetchOrders(params.value)
    } catch (error) {
      console.error('Failed to fetch orders', error)
    } finally {
      loading.value = false
    }
  }

  function updateParams(newParams) {
    params.value = { ...params.value, ...newParams, page: 1 }
    getOrders()
  }

  return {
    rows,
    loading,
    params,
    getOrders,
    updateParams,
  }
}
