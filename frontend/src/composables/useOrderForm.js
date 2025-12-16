import { ref } from 'vue'
import { placeOrderApi } from '@/api/orders.api'

export function useOrderForm() {
  const loading = ref(false)
  const error = ref(null)

  const placeOrder = async ({ symbol, side, amount, price }) => {
    loading.value = true
    error.value = null

    try {
      const { data } = await placeOrderApi({ symbol, side, amount, price })
      return data
    } catch (err) {
      console.error('Failed to place order:', err)
      error.value = err.response?.data?.message || 'Something went wrong'
      throw err
    } finally {
      loading.value = false
    }
  }

  return { placeOrder, loading, error }
}
