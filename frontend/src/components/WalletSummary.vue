<script setup>
defineProps({ user: Object })
</script>

<template>
  <div class="bg-white rounded-xl shadow p-4">
    <h2 class="font-semibold mb-3">Wallet</h2>

    <p class="text-sm mb-2">
      USD Balance:
      <strong>${{ user.usd_balance.toFixed(2) }}</strong>
    </p>

    <div class="text-sm space-y-1">
      <div v-for="(asset, symbol) in user.assets" :key="symbol">
        {{ symbol }}: {{ asset.amount }} (locked {{ asset.locked }})
      </div>
    </div>
  </div>
</template>
