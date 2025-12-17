<script setup>
import { ref } from 'vue'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import { useOrderForm } from '@/composables/useOrderForm'
import { useOrders } from '@/composables/useOrders'
import { useAuth } from '@/composables/useAuth'

const schema = yup.object({
  symbol: yup.string().required('Symbol is required'),
  side: yup.string().oneOf(['buy', 'sell']).required('Side is required'),
  amount: yup.number().positive('Amount must be greater than 0').required('Amount is required'),
  price: yup.number().positive('Price must be greater than 0').required('Price is required'),
})

const { handleSubmit, errors, resetForm  } = useForm({ validationSchema: schema })

const { value: symbol } = useField('symbol', schema.fields.symbol, { initialValue: 'BTC' })
const { value: side } = useField('side', schema.fields.side, { initialValue: 'buy' })
const { value: amount } = useField('amount', schema.fields.amount)
const { value: price } = useField('price', schema.fields.price)

const { placeOrder, loading, error } = useOrderForm()
const { getOrders } = useOrders()
const { fetchUser } = useAuth()

const submitOrder = handleSubmit(async (values) => {
  try {
    await placeOrder({
      symbol: values.symbol,
      side: values.side,
      amount: parseFloat(values.amount),
      price: parseFloat(values.price),
    })
    
    resetForm({
      values: {
        symbol: 'BTC',
        side: 'buy',
      },
    })
    
    getOrders()
    fetchUser()
    alert('Order placed successfully!')
  } catch (err) {
    console.log(err)
    alert(error.value)
  }
})
</script>

<template>
  <div class="bg-white p-6 rounded-xl shadow-md space-y-4 max-w-md">
    <h2 class="text-lg font-semibold">Place Order</h2>

    <form @submit.prevent="submitOrder" class="space-y-4">
      
      <div>
        <label class="block font-medium mb-1">Symbol</label>
        <select v-model="symbol" class="input w-full">
          <option value="BTC">BTC</option>
          <option value="ETH">ETH</option>
        </select>
        <span class="text-red-500 text-sm">{{ errors.symbol }}</span>
      </div>
      
      <div>
        <label class="block font-medium mb-1">Side</label>
        <select v-model="side" class="input w-full">
          <option value="buy">Buy</option>
          <option value="sell">Sell</option>
        </select>
        <span class="text-red-500 text-sm">{{ errors.side }}</span>
      </div>
      
      <div>
        <label class="block font-medium mb-1">Amount</label>
        <input v-model="amount" type="number" placeholder="Amount" class="input w-full" />
        <span class="text-red-500 text-sm">{{ errors.amount }}</span>
      </div>
      
      <div>
        <label class="block font-medium mb-1">Price</label>
        <input v-model="price" type="number" placeholder="Price" class="input w-full" />
        <span class="text-red-500 text-sm">{{ errors.price }}</span>
      </div>
      
      <button
        type="submit"
        :disabled="loading"
        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? 'Placing...' : 'Place Order' }}
      </button>
    </form>
  </div>
</template>
