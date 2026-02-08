<script setup>
import { ref, computed, h } from 'vue'
import {
  useVueTable,
  getCoreRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  getFilteredRowModel,
  FlexRender
} from '@tanstack/vue-table'
import { valueUpdater, formatDuration } from '@/lib/utils'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
  TableEmpty
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import { Skeleton } from '@/components/ui/skeleton'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu'
import {
  ArrowUpDown,
  MoreHorizontal,
  Pencil,
  Trash2,
  Eye,
  Search
} from 'lucide-vue-next'

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['edit', 'delete', 'view'])

const sorting = ref([])
const columnFilters = ref([])
const globalFilter = ref('')

const columns = [
  {
    accessorKey: 'title',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
      }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('title'))
  },
  {
    accessorKey: 'duration',
    header: 'Duration',
    cell: ({ row }) => formatDuration(row.getValue('duration'))
  },
  {
    accessorKey: 'release_year',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
      }, () => ['Year', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => row.getValue('release_year')
  },
  {
    accessorKey: 'genre',
    header: 'Genre',
    cell: ({ row }) => {
      const genre = row.getValue('genre')
      if (!genre) return h('span', { class: 'text-muted-foreground' }, '-')
      const genres = genre.split(',').map(g => g.trim()).slice(0, 2)
      return h('div', { class: 'flex flex-wrap gap-1' }, 
        genres.map(g => h(Badge, { variant: 'secondary', class: 'text-xs' }, () => g))
      )
    }
  },
  {
    accessorKey: 'director',
    header: 'Director',
    cell: ({ row }) => row.getValue('director') || '-'
  },
  {
    id: 'actions',
    enableHiding: false,
    cell: ({ row }) => {
      const movie = row.original
      return h(DropdownMenu, null, {
        default: () => [
          h(DropdownMenuTrigger, { asChild: true }, () =>
            h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () =>
              h(MoreHorizontal, { class: 'h-4 w-4' })
            )
          ),
          h(DropdownMenuContent, { align: 'end' }, () => [
            h(DropdownMenuLabel, null, () => 'Actions'),
            h(DropdownMenuItem, { onClick: () => emit('view', movie) }, () => [
              h(Eye, { class: 'mr-2 h-4 w-4' }),
              'View Details'
            ]),
            h(DropdownMenuSeparator),
            h(DropdownMenuItem, { onClick: () => emit('edit', movie) }, () => [
              h(Pencil, { class: 'mr-2 h-4 w-4' }),
              'Edit'
            ]),
            h(DropdownMenuItem, { 
              class: 'text-destructive focus:text-destructive',
              onClick: () => emit('delete', movie) 
            }, () => [
              h(Trash2, { class: 'mr-2 h-4 w-4' }),
              'Delete'
            ])
          ])
        ]
      })
    }
  }
]

const table = useVueTable({
  get data() {
    return props.data
  },
  columns,
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
  onGlobalFilterChange: updaterOrValue => valueUpdater(updaterOrValue, globalFilter),
  state: {
    get sorting() { return sorting.value },
    get columnFilters() { return columnFilters.value },
    get globalFilter() { return globalFilter.value }
  }
})

const pageIndex = computed(() => table.getState().pagination.pageIndex)
const pageCount = computed(() => table.getPageCount())
</script>

<template>
  <div class="space-y-4">
    <!-- Search -->
    <div class="flex items-center gap-2">
      <div class="relative flex-1 max-w-sm">
        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          v-model="globalFilter"
          placeholder="Search movies..."
          class="pl-9"
        />
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender
                v-if="!header.isPlaceholder"
                :render="header.column.columnDef.header"
                :props="header.getContext()"
              />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <!-- Loading state -->
          <template v-if="loading">
            <TableRow v-for="i in 5" :key="i">
              <TableCell v-for="j in columns.length" :key="j">
                <Skeleton class="h-6 w-full" />
              </TableCell>
            </TableRow>
          </template>
          
          <!-- Data rows -->
          <template v-else-if="table.getRowModel().rows?.length">
            <TableRow
              v-for="row in table.getRowModel().rows"
              :key="row.id"
              :data-state="row.getIsSelected() && 'selected'"
            >
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender
                  :render="cell.column.columnDef.cell"
                  :props="cell.getContext()"
                />
              </TableCell>
            </TableRow>
          </template>
          
          <!-- Empty state -->
          <TableEmpty v-else :colspan="columns.length">
            No movies found.
          </TableEmpty>
        </TableBody>
      </Table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
      <div class="text-sm text-muted-foreground">
        {{ table.getFilteredRowModel().rows.length }} movie(s) total
      </div>
      <div class="flex items-center gap-2">
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanPreviousPage()"
          @click="table.previousPage()"
        >
          Previous
        </Button>
        <span class="text-sm text-muted-foreground">
          Page {{ pageIndex + 1 }} of {{ pageCount || 1 }}
        </span>
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanNextPage()"
          @click="table.nextPage()"
        >
          Next
        </Button>
      </div>
    </div>
  </div>
</template>
