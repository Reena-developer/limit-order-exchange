<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { login, loading } = useAuth()

const email = ref('')
const password = ref('')
const error = ref(null)

const submit = async () => {
  error.value = null
  try {
    await login(email.value, password.value)
    router.push({ name: 'Dashboard' })
  } catch (e) {
    error.value = e?.message || 'Invalid email or password'
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">
      
      <!-- Header -->
      <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Welcome Back</h2>
        <p class="text-sm text-gray-500 mt-1">
          Sign in to your account
        </p>
      </div>

      <!-- Error -->
      <div
        v-if="error"
        class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2"
      >
        {{ error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Email address
          </label>
          <input
            v-model="email"
            type="email"
            required
            class="input w-full"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Password
          </label>
          <input
            v-model="password"
            type="password"
            required
            class="input w-full"
            placeholder="••••••••"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="btn w-full flex items-center justify-center gap-2"
        >
          <span v-if="!loading">Login</span>
          <span v-else class="animate-pulse">Signing in...</span>
        </button>
      </form>

      <!-- Footer -->
      <p class="text-center text-sm text-gray-500 mt-6">
        Don’t have an account?
        <router-link
          to="/register"
          class="text-indigo-600 hover:underline font-medium"
        >
          Create one
        </router-link>
      </p>

    </div>
  </div>
</template>
