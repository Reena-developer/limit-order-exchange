<template>
  <nav class="flex justify-between p-4 bg-gray-100">
    <div>Limit Order Exchange</div>
    <div>
      <button v-if="user" @click="logout" class="btn">Logout</button>
    </div>
  </nav>
</template>

<script setup>
import { useAuth } from '@/composables/useAuth'
const { user, logout } = useAuth()
</script>
