<script setup>
import { onMounted } from 'vue'
import echo from '@/bootstrap/echo'
import Navbar from '@/components/Navbar.vue'
import WalletSummary from '@/components/WalletSummary.vue'
import OrderForm from '@/components/OrderForm.vue'
import OrdersTable from '@/components/OrdersTable.vue'
import { useOrders } from '@/composables/useOrders'
import { useAuth } from '@/composables/useAuth'
import { orderColumns } from '@/components/orders.columns'

const { user, fetchUser } = useAuth()
const { rows, meta, loading, params, getOrders, updateParams, changePage } = useOrders()

onMounted(async () => {
  await fetchUser()
  await getOrders()

  const userId = user.value.id
  echo.private(`user.${user.value.id}`)
    .listen('OrderMatched', (event) => {
      console.log('Private order matched:', event)
      fetchUser()   
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
