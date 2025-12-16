<script setup>
defineProps({ orderBook: Object })
</script>

<template>
  <div class="bg-white rounded-xl shadow p-4">
    <h2 class="font-semibold mb-3">Orderbook</h2>

    <div class="grid grid-cols-2 gap-4 text-sm">
      <div>
        <h3 class="font-medium text-green-600 mb-1">Buy</h3>
        <div v-for="(b, i) in orderBook.buy" :key="i">
          {{ b.price }} / {{ b.amount }}
        </div>
      </div>

      <div>
        <h3 class="font-medium text-red-600 mb-1">Sell</h3>
        <div v-for="(s, i) in orderBook.sell" :key="i">
          {{ s.price }} / {{ s.amount }}
        </div>
      </div>
    </div>
  </div>
</template>
