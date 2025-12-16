import { ref } from 'vue'
import { fetchOrdersApi } from '@/api/orders.api'

const rows = ref([])
const meta = ref(null)
const loading = ref(false)
const params = ref({
  symbol: null,
  side: null,
  status: null,
  sortBy: 'id',
  sortDir: 'desc',
  page: 1,
  perPage: 10
})

export function useOrders() {

  const getOrders = async () => {
    loading.value = true
    try {
      const response = await fetchOrdersApi(params.value)
      rows.value = response.data.data
      meta.value = response.data.pagination
    } catch (error) {
      console.error('Failed to fetch orders', error)
    } finally {
      loading.value = false
    }
  }

  const updateParams = (newParams) => {
    params.value = { ...params.value, ...newParams, page: 1 }
    getOrders()
  }

  const changePage = (page) => {
    params.value.page = page
    getOrders()
  }

  function handleSort(columnKey) {
    let direction = 'asc'
    if (sortState.value.column === columnKey) {
      direction = sortState.value.direction === 'asc' ? 'desc' : 'asc'
    }
    sortState.value = { column: columnKey, direction }

    props.onParamsChange?.({
      sortBy: columnKey,
      sortDir: direction,
    })
  }
  
  return { rows, meta, params, loading, getOrders, updateParams, changePage }
}
