<script setup>
import { onMounted } from 'vue'
import echo from '@/bootstrap/echo'
import Navbar from '@/components/Navbar.vue'
import WalletSummary from '@/components/WalletSummary.vue'
import OrderForm from '@/components/OrderForm.vue'
import OrderBook from '@/components/OrderBook.vue'
import OrdersTable from '@/components/OrdersTable.vue'
import { useOrders } from '@/composables/useOrders'
import { useAuth } from '@/composables/useAuth'
import { orderColumns } from '@/components/orders.columns'
import { useMockData } from '@/composables/useMockData'
const orderBook  = useMockData()

const { user, fetchUser } = useAuth()
const { rows, meta, loading, params, getOrders, updateParams, changePage } = useOrders()

onMounted(async () => {
  await fetchUser()
  await getOrders()
})

onMounted(() => {
  echo.channel('trades')
    .listen('TradeExecuted', e => {
      console.log('Trade executed:', e)
      getOrders()
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
        :loading="loading"
        :meta="meta"
        :params="params"
        :columns="orderColumns"
        @params-change="updateParams"
        @page-change="changePage"
      />
    </div>
  </div>
</template>
