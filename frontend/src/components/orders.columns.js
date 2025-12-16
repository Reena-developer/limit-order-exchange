export const orderColumns = [
  { accessorKey: 'symbol', header: 'Symbol' },
  {
    accessorKey: 'side',
    header: 'Side',
    cell: info => {
      const value = info.getValue()
      const colorClass = value === 'buy' ? 'text-green-600' : 'text-red-600'
      return { text: value.toUpperCase(), class: colorClass + ' font-semibold' }
    },
  },
  { accessorKey: 'price', header: 'Price' },
  { accessorKey: 'amount', header: 'Amount' },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: info => {
      const value = info.getValue()
      let colorClass = 'text-gray-500'
      if (value === 'open') colorClass = 'text-yellow-600'
      else if (value === 'filled') colorClass = 'text-green-600'
      else if (value === 'cancelled') colorClass = 'text-gray-400'
      return { text: value, class: colorClass }
    },
  },
]
