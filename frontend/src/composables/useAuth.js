import { ref } from 'vue'
import api from '@/services/api'

const user = ref(null)
const loading = ref(false)

export function useAuth() {
  async function login(email, password) {
    loading.value = true
    try {
      const res = await api.post('/auth/login', { email, password })
      user.value = res.data.data
      return res
    } catch (e) {
      throw e.response?.data || e
    } finally {
      loading.value = false
    }
  }

  async function register(data) {
    loading.value = true
    try {
      const res = await api.post('/auth/register', data)
      user.value = res.data.data
      return res
    } catch (e) {
      throw e.response?.data || e
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    try {
      await api.post('/auth/logout')
      user.value = null
    } catch (e) {
      console.error(e)
    } finally {
      loading.value = false
    }
  }

  return { user, loading, login, register, logout }
}
