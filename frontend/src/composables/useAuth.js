import { ref, computed } from 'vue'
import { loginApi, registerApi, meApi, logoutApi } from '@/api/auth.api'

const user = ref(JSON.parse(localStorage.getItem('user')))
const loading = ref(false)

export function useAuth() {

  const isAuthenticated = computed(() => !!user.value)

  const login = async (email, password) => {
    loading.value = true
    try {
      const { data } = await loginApi({ email, password })
      localStorage.setItem('token', data.data.token)
      localStorage.setItem('user', JSON.stringify(data.data.user))
      user.value = data.data.user
    } finally {
      loading.value = false
    }
  }

  const register = async payload => {
    loading.value = true
    try {
      const { data } = await registerApi(payload)
      localStorage.setItem('token', data.data.token)
      localStorage.setItem('user', JSON.stringify(data.data.user))
      user.value = data.data.user
    } finally {
      loading.value = false
    }
  }

  const fetchUser = async () => {
    if (!localStorage.getItem('token')) return
    try {
      const { data } = await meApi()
      user.value = data.data
      localStorage.setItem('user', JSON.stringify(data.data))
    } catch {
      logout()
    }
  }

  const logout = async () => {
    try {
      await logoutApi()
    } catch {}
    localStorage.clear()
    user.value = null
    window.location.href = '/login'
  }

  return {
    user,
    loading,
    isAuthenticated,
    login,
    register,
    fetchUser,
    logout,
  }
}
