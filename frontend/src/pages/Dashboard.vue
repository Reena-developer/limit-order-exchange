<script setup>
import { onMounted } from 'vue'
import echo from '@/bootstrap/echo'
import Navbar from '@/components/Navbar.vue'
import WalletSummary from '@/components/WalletSummary.vue'
import OrderForm from '@/components/OrderForm.vue'
import OrderBook from '@/components/OrderBook.vue'
import OrdersTable from '@/components/OrdersTable.vue'
import { useOrders } from '@/composables/useOrders'
import { useMockData } from '@/composables/useMockData'
import { orderColumns } from '@/components/orders.columns'

const { user, orderBook } = useMockData()
const { rows, loading, getOrders, updateParams } = useOrders()

onMounted(getOrders)
onMounted(() => {
  echo.channel('trades')
    .listen('TradeExecuted', (e) => {
      console.log('Trade executed:', e)
    })
})
</script>

<template>
  <Navbar />

  <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="space-y-6">
      <WalletSummary :user="user" />
      <OrderForm />
    </div>

    <div class="md:col-span-2 space-y-6">
      <OrderBook :orderBook="orderBook" />
      <OrdersTable
        :rows="rows"
        :columns="orderColumns"
        :loading="loading"
        :onParamsChange="updateParams"
      />
    </div>
  </div>
</template>
