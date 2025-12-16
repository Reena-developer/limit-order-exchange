<script setup>
import { ref } from 'vue'
import {
  createColumnHelper,
  useVueTable,
  getCoreRowModel,
} from '@tanstack/vue-table'

const props = defineProps({
  rows: { type: Array, required: true },
  columns: { type: Array, required: true },
  loading: Boolean,
  onParamsChange: Function,
})

const sortState = ref({ column: 'id', direction: 'desc' })

const columnHelper = createColumnHelper()

const table = useVueTable({
  data: () => props.rows,
  columns: props.columns.map(col =>
    columnHelper.accessor(col.accessorKey, {
      header: col.header,
      cell: col.cell || (info => info.getValue()),
    })
  ),
  getCoreRowModel: getCoreRowModel(),
})

function handleSort(columnKey) {
  if (sortState.value.column === columnKey) {
    sortState.value.direction =
      sortState.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortState.value.column = columnKey
    sortState.value.direction = 'asc'
  }

  props.onParamsChange({
    sortBy: columnKey,
    sortDir: sortState.value.direction,
  })
}
</script>

<template>
  <div class="bg-white rounded-xl shadow p-4">
    
    <div class="flex gap-4 mb-4">

      <select @change="e => props.onParamsChange({ symbol: e.target.value })" class="input">
        <option value="all">All Symbols</option>
        <option value="BTC">BTC</option>
        <option value="ETH">ETH</option>
      </select>

      <select @change="e => props.onParamsChange({ side: e.target.value })" class="input">
        <option value="all">All Sides</option>
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
      </select>

      <select @change="e => props.onParamsChange({ status: e.target.value })" class="input">
        <option value="all">All Status</option>
        <option value="open">Open</option>
        <option value="filled">Filled</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    
    <table class="w-full text-sm">
      <thead class="border-b text-gray-500">
        <tr>
          <th
            v-for="header in table.getHeaderGroups()[0].headers"
            :key="header.id"
            class="text-left py-2 cursor-pointer"
            @click="handleSort(header.column.id)"
          >
            {{ header.column.columnDef.header }}
            <span v-if="sortState.column === header.column.id">
              {{ sortState.direction === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-if="loading">
          <td colspan="10" class="text-center py-6">Loading...</td>
        </tr>

        <tr
          v-for="row in table.getRowModel().rows"
          :key="row.id"
          class="border-b last:border-0"
        >
          <td
            v-for="cell in row.getVisibleCells()"
            :key="cell.id"
            class="py-2"
          >
            <span
              v-if="typeof cell.getValue() === 'object'"
              :class="cell.getValue().class"
            >
              {{ cell.getValue().text }}
            </span>
            <span v-else>
              {{ cell.getValue() }}
            </span>
          </td>
        </tr>

        <tr v-if="!loading && rows.length === 0">
          <td colspan="10" class="text-center py-6 text-gray-400">
            No orders found
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
