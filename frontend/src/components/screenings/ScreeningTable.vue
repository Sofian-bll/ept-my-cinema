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
import { valueUpdater, formatDateTime, formatTime, formatDuration } from '@/lib/utils'
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
  Search,
  Clock,
  Film,
  DoorOpen
} from 'lucide-vue-next'

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  },
  movies: {
    type: Array,
    default: () => []
  },
  rooms: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['edit', 'delete'])

const sorting = ref([{ id: 'start_time', desc: false }])
const columnFilters = ref([])
const globalFilter = ref('')

function getMovie(movieId) {
  return props.movies.find(m => m.id === movieId)
}

function getRoom(roomId) {
  return props.rooms.find(r => r.id === roomId)
}

const columns = [
  {
    accessorKey: 'start_time',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
      }, () => ['Date & Time', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
      const startTime = row.getValue('start_time')
      const endTime = row.original.end_time
      return h('div', { class: 'space-y-1' }, [
        h('div', { class: 'font-medium' }, formatDateTime(startTime)),
        h('div', { class: 'text-xs text-muted-foreground flex items-center gap-1' }, [
          h(Clock, { class: 'h-3 w-3' }),
          `${formatTime(startTime)} - ${formatTime(endTime)}`
        ])
      ])
    }
  },
  {
    accessorKey: 'movies_id',
    header: 'Movie',
    cell: ({ row }) => {
      const movie = getMovie(row.getValue('movies_id'))
      if (!movie) return h('span', { class: 'text-muted-foreground' }, 'Unknown')
      return h('div', { class: 'flex items-center gap-2' }, [
        h(Film, { class: 'h-4 w-4 text-muted-foreground' }),
        h('div', null, [
          h('div', { class: 'font-medium' }, movie.title),
          h('div', { class: 'text-xs text-muted-foreground' }, formatDuration(movie.duration))
        ])
      ])
    }
  },
  {
    accessorKey: 'room_id',
    header: 'Room',
    cell: ({ row }) => {
      const room = getRoom(row.getValue('room_id'))
      if (!room) return h('span', { class: 'text-muted-foreground' }, 'Unknown')
      return h('div', { class: 'flex items-center gap-2' }, [
        h(DoorOpen, { class: 'h-4 w-4 text-muted-foreground' }),
        h('div', null, [
          h('div', { class: 'font-medium' }, room.name),
          room.type && h(Badge, { variant: 'secondary', class: 'text-xs' }, () => room.type)
        ])
      ])
    }
  },
  {
    id: 'status',
    header: 'Status',
    cell: ({ row }) => {
      const startTime = new Date(row.original.start_time)
      const endTime = new Date(row.original.end_time)
      const now = new Date()
      
      if (now < startTime) {
        return h(Badge, { variant: 'outline', class: 'text-green-500 border-green-500' }, () => 'Upcoming')
      } else if (now >= startTime && now <= endTime) {
        return h(Badge, { variant: 'default' }, () => 'Now Playing')
      } else {
        return h(Badge, { variant: 'secondary' }, () => 'Completed')
      }
    }
  },
  {
    id: 'actions',
    enableHiding: false,
    cell: ({ row }) => {
      const screening = row.original
      return h(DropdownMenu, null, {
        default: () => [
          h(DropdownMenuTrigger, { asChild: true }, () =>
            h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () =>
              h(MoreHorizontal, { class: 'h-4 w-4' })
            )
          ),
          h(DropdownMenuContent, { align: 'end' }, () => [
            h(DropdownMenuLabel, null, () => 'Actions'),
            h(DropdownMenuItem, { onClick: () => emit('edit', screening) }, () => [
              h(Pencil, { class: 'mr-2 h-4 w-4' }),
              'Edit'
            ]),
            h(DropdownMenuSeparator),
            h(DropdownMenuItem, { 
              class: 'text-destructive focus:text-destructive',
              onClick: () => emit('delete', screening) 
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
    <div class="flex items-center gap-2">
      <div class="relative flex-1 max-w-sm">
        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          v-model="globalFilter"
          placeholder="Search screenings..."
          class="pl-9"
        />
      </div>
    </div>

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
          <template v-if="loading">
            <TableRow v-for="i in 5" :key="i">
              <TableCell v-for="j in columns.length" :key="j">
                <Skeleton class="h-10 w-full" />
              </TableCell>
            </TableRow>
          </template>
          
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
          
          <TableEmpty v-else :colspan="columns.length">
            No screenings found.
          </TableEmpty>
        </TableBody>
      </Table>
    </div>

    <div class="flex items-center justify-between">
      <div class="text-sm text-muted-foreground">
        {{ table.getFilteredRowModel().rows.length }} screening(s) total
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
