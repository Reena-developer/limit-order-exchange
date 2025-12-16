import api from '@/services/api'

export const loginApi = data => api.post('/auth/login', data)
export const registerApi = data => api.post('/auth/register', data)
export const meApi = () => api.get('/profile')
export const logoutApi = () => api.post('/auth/logout')
