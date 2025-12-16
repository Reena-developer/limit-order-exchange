<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { register, loading } = useAuth()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')

const submit = async () => {
  error.value = ''
  try {
    await register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    })
    router.push({ name: 'Dashboard' })
  } catch (e) {
    error.value = e?.message || 'Registration failed'
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-100">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">

      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
          Create Account
        </h1>
        <p class="text-sm text-gray-500 mt-1">
          Join Limit Exchange today
        </p>
      </div>

      <!-- Error -->
      <div
        v-if="error"
        class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-2"
      >
        {{ error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-4">

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Full Name
          </label>
          <input
            v-model="name"
            type="text"
            required
            placeholder="John Doe"
            class="input w-full"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Email
          </label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="you@example.com"
            class="input w-full"
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
            placeholder="••••••••"
            class="input w-full"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Confirm Password
          </label>
          <input
            v-model="password_confirmation"
            type="password"
            required
            placeholder="••••••••"
            class="input w-full"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="btn w-full flex items-center justify-center gap-2"
        >
          <span v-if="!loading">Create Account</span>
          <span v-else class="animate-pulse">Creating...</span>
        </button>
      </form>

      <!-- Footer -->
      <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <router-link
          to="/login"
          class="text-indigo-600 hover:underline font-medium"
        >
          Login
        </router-link>
      </p>

    </div>
  </div>
</template>
