<script setup>
import { ref, watch, computed } from 'vue'
import {
  createColumnHelper,
  useVueTable,
  getCoreRowModel,
} from '@tanstack/vue-table'

const props = defineProps({
  rows: { type: Array, required: true },
  columns: { type: Array, required: true },
  loading: Boolean,
  meta: Object,            
  params: Object,          
  onParamsChange: Function,
  onPageChange: Function,
})

const sortState = ref({
  column: props.params?.sortBy || 'id',
  direction: props.params?.sortDir || 'desc',
})

const columnHelper = createColumnHelper()

const tableColumns = computed(() => {
  if (!props.columns || props.columns.length === 0) {
    console.warn('No columns provided to OrdersTable')
    return []
  }

  return props.columns.map(col => {
    return columnHelper.accessor(col.accessorKey || 'id', {
      header: col.header || col.accessorKey,
      cell: col.cell ? col.cell : ((info) => {
        const value = info.getValue()
        return typeof value === 'object' ? value?.text || JSON.stringify(value) : value
      }),
    })
  })
})

const table = computed(() => {
  if (!tableColumns.value || tableColumns.value.length === 0) {
    return null
  }
  
  return useVueTable({
    data: props.rows || [],
    columns: tableColumns.value,
    getCoreRowModel: getCoreRowModel(),
  })
})

watch(
  () => props.params,
  (newParams) => {
    if (newParams?.sortBy) sortState.value.column = newParams.sortBy
    if (newParams?.sortDir) sortState.value.direction = newParams.sortDir
  },
  { deep: true }
)

function handleSort(columnKey) {
  let direction = 'asc'
  if (sortState.value.column === columnKey) {
    direction = sortState.value.direction === 'asc' ? 'desc' : 'asc'
  }
  sortState.value = { column: columnKey, direction }
  props.onParamsChange?.({ sortBy: columnKey, sortDir: direction })
}

function handleFilter(key, value) {
  props.onParamsChange?.({ [key]: value })
}

function prevPage() {
  if (props.params.page > 1) props.onPageChange?.(props.params.page - 1)
}

function nextPage() {
  if (props.params.page < (props.meta?.total_pages || 1)) {
    props.onPageChange?.(props.params.page + 1)
  }
}

</script>

<template>
  <div class="bg-white rounded-xl shadow p-4">

    <!-- Filters -->
    <div class="flex gap-4 mb-4 flex-wrap">
      <select @change="e => handleFilter('symbol', e.target.value)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <option value="">All Symbols</option>
        <option value="BTC">BTC</option>
        <option value="ETH">ETH</option>
      </select>

      <select @change="e => handleFilter('side', e.target.value)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <option value="">All Sides</option>
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
      </select>

      <select @change="e => handleFilter('status', e.target.value)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <option value="">All Status</option>
        <option value="open">Open</option>
        <option value="filled">Filled</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-sm" v-if="table">
        <thead class="border-b bg-gray-50">
          <tr>
            <th
              v-for="header in table.getHeaderGroups()[0]?.headers || []"
              :key="header.id"
              class="text-left py-3 px-4 cursor-pointer font-semibold text-gray-700 hover:bg-gray-100"
              @click="handleSort(header.column.id)"
            >
              <div class="flex items-center gap-2">
                {{ header.column.columnDef.header }}
                <span v-if="sortState.column === header.column.id" class="text-xs">
                  {{ sortState.direction === 'asc' ? '↑' : '↓' }}
                </span>
              </div>
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-if="props.loading">
            <td :colspan="tableColumns.length" class="text-center py-6 text-gray-500">
              Loading...
            </td>
          </tr>

          <tr 
            v-for="row in table.getRowModel().rows" 
            :key="row.id" 
            class="border-b hover:bg-gray-50 last:border-0"
          >
            <td 
              v-for="cell in row.getVisibleCells()" 
              :key="cell.id" 
              class="py-3 px-4"
            >
              <!-- Handle custom cell renderers with styling -->
              <template v-if="typeof cell.getValue() === 'object' && cell.getValue() !== null">
                <span :class="cell.getValue().class">
                  {{ cell.getValue().text }}
                </span>
              </template>
              
              <!-- Handle plain text values -->
              <template v-else>
                {{ cell.getValue() }}
              </template>
            </td>
          </tr>

          <tr v-if="!props.loading && (!props.rows || props.rows.length === 0)">
            <td :colspan="tableColumns.length" class="text-center py-6 text-gray-400">
              No orders found
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Fallback if table fails -->
      <div v-else class="p-4 bg-red-50 border border-red-200 rounded text-red-700">
        Error: Table failed to initialize. Check console for details.
      </div>
    </div>

    <!-- Pagination - Only shows when there's data -->
    <div v-if="props.rows && props.rows.length > 0" class="flex justify-between items-center mt-6">
      <button 
        @click="prevPage" 
        :disabled="props.params.page <= 1"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 transition"
      >
        Prev
      </button>
      <span class="text-sm text-gray-600">
        Page {{ props.params.page }} of {{ props.meta?.total_pages || 1 }}
      </span>
      <button 
        @click="nextPage" 
        :disabled="props.params.page >= (props.meta?.total_pages || 1)"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 transition"
      >
        Next
      </button>
    </div>
  </div>
</template>