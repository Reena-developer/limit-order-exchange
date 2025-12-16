import { ref } from 'vue'
export function useMockData() {
    const user = ref({
        usd_balance: 12500.75,
        assets: {
            BTC: { amount: 0.25, locked: 0.05 },
            ETH: { amount: 4.2, locked: 1.0 },
        },
    })

    const orders = ref([
        { id: 1, symbol: 'BTC', side: 'buy', price: 95000, amount: 0.01, status: 'open' },
        { id: 2, symbol: 'ETH', side: 'sell', price: 3200, amount: 0.5, status: 'filled' },
    ])

    const orderBook = ref({
        buy: [
        { price: 94900, amount: 0.03 },
        { price: 94850, amount: 0.05 },
        ],
        sell: [
        { price: 95100, amount: 0.02 },
        { price: 95200, amount: 0.01 },
        ],
    })

  return { user, orders, orderBook }
}