import api from '@/services/api'

export const fetchOrdersApi = params => api.get('/orders', { params })
export const placeOrderApi = (data) => api.post('/orders', data)