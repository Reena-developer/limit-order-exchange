import api from '@/services/api'

export async function fetchOrders(params) {
  const response = await api.get('/orders', { params })
  return response.data.data
}
