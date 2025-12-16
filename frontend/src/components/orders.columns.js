export const orderColumns = [
  { 
    accessorKey: 'symbol', 
    header: 'Symbol',
    cell: info => {
      const value = info.getValue()
      return { 
        text: value, 
        class: 'font-semibold text-gray-800' 
      }
    }
  },
  {
    accessorKey: 'side',
    header: 'Side',
    cell: info => {
      const value = info.getValue()
      const colorClass = value === 'buy' ? 'text-green-600' : 'text-red-600'
      return { 
        text: value.toUpperCase(), 
        class: colorClass + ' font-semibold' 
      }
    },
  },
  { 
    accessorKey: 'price', 
    header: 'Price',
    cell: info => {
      const value = info.getValue()
      return { 
        text: typeof value === 'number' ? value.toFixed(2) : value, 
        class: 'text-gray-700' 
      }
    }
  },
  { 
    accessorKey: 'amount', 
    header: 'Amount',
    cell: info => {
      const value = info.getValue()
      return { 
        text: typeof value === 'number' ? value.toFixed(4) : value, 
        class: 'text-gray-700' 
      }
    }
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: info => {
      const value = info.getValue()
      let colorClass = 'text-gray-500 bg-gray-100'
      let badgeClass = 'px-2 py-1 rounded-full text-xs font-medium'
      
      if (value === 'open') {
        colorClass = 'text-yellow-700 bg-yellow-100'
      } else if (value === 'filled') {
        colorClass = 'text-green-700 bg-green-100'
      } else if (value === 'cancelled') {
        colorClass = 'text-gray-600 bg-gray-200'
      }
      
      return { 
        text: value.charAt(0).toUpperCase() + value.slice(1), 
        class: badgeClass + ' ' + colorClass
      }
    },
  },
  {
    accessorKey: 'createdAt',
    header: 'Created At',
    cell: info => {
      const value = info.getValue()
      const date = new Date(value)
      const formatted = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
      return { 
        text: formatted, 
        class: 'text-gray-600 text-xs' 
      }
    }
  }
]