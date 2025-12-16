<script setup>
import { ref } from 'vue'

const symbol = ref('BTC')
const side = ref('buy')
const price = ref('')
const amount = ref('')

function submit() {
  alert(`${side.value} ${amount.value} ${symbol.value} @ ${price.value}`)
}
</script>

<template>
  <div class="bg-white rounded-xl shadow p-4">
    <h2 class="font-semibold mb-3">Place Limit Order</h2>

    <select v-model="symbol" class="input">
      <option>BTC</option>
      <option>ETH</option>
    </select>

    <select v-model="side" class="input mt-2">
      <option value="buy">Buy</option>
      <option value="sell">Sell</option>
    </select>

    <input class="input mt-2" type="number" placeholder="Price" v-model="price" />
    <input class="input mt-2" type="number" placeholder="Amount" v-model="amount" />

    <button @click="submit" class="btn-primary mt-4 w-full">
      Place Order
    </button>
  </div>
</template>
