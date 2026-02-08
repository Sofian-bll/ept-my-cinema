<script setup>
import { ref, computed, watch } from 'vue'
import { formatTime, formatDuration } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { ScrollArea } from '@/components/ui/scroll-area'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from '@/components/ui/select'
import {
  ChevronLeft,
  ChevronRight,
  CalendarDays,
  Clock,
  Film,
  DoorOpen,
  Pencil,
  Trash2
} from 'lucide-vue-next'

const props = defineProps({
  screenings: {
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

const emit = defineEmits(['edit', 'delete', 'add'])

const currentDate = ref(new Date())
const selectedRoomId = ref('all')

/**
 * Get current week dates
 */
const weekDates = computed(() => {
  const dates = []
  const startOfWeek = new Date(currentDate.value)
  startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay() + 1) // Monday
  
  for (let i = 0; i < 7; i++) {
    const date = new Date(startOfWeek)
    date.setDate(date.getDate() + i)
    dates.push(date)
  }
  return dates
})

/**
 * Format date for display
 */
function formatWeekDate(date) {
  return date.toLocaleDateString('en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric'
  })
}

/**
 * Check if date is today
 */
function isToday(date) {
  const today = new Date()
  return date.toDateString() === today.toDateString()
}

/**
 * Get screenings for a specific date and room
 */
function getScreeningsForDate(date) {
  const dateStr = date.toISOString().split('T')[0]
  return props.screenings
    .filter(s => {
      const matches = s.start_time.startsWith(dateStr)
      if (selectedRoomId.value === 'all') return matches
      return matches && s.room_id.toString() === selectedRoomId.value
    })
    .sort((a, b) => new Date(a.start_time) - new Date(b.start_time))
}

/**
 * Get movie by ID
 */
function getMovie(movieId) {
  return props.movies.find(m => m.id === movieId)
}

/**
 * Get room by ID
 */
function getRoom(roomId) {
  return props.rooms.find(r => r.id === roomId)
}

/**
 * Navigate to previous week
 */
function previousWeek() {
  const newDate = new Date(currentDate.value)
  newDate.setDate(newDate.getDate() - 7)
  currentDate.value = newDate
}

/**
 * Navigate to next week
 */
function nextWeek() {
  const newDate = new Date(currentDate.value)
  newDate.setDate(newDate.getDate() + 7)
  currentDate.value = newDate
}

/**
 * Go to today
 */
function goToToday() {
  currentDate.value = new Date()
}

/**
 * Week range display
 */
const weekRangeDisplay = computed(() => {
  const start = weekDates.value[0]
  const end = weekDates.value[6]
  const startMonth = start.toLocaleDateString('en-US', { month: 'short' })
  const endMonth = end.toLocaleDateString('en-US', { month: 'short' })
  const year = start.getFullYear()
  
  if (startMonth === endMonth) {
    return `${start.getDate()} - ${end.getDate()} ${startMonth} ${year}`
  }
  return `${start.getDate()} ${startMonth} - ${end.getDate()} ${endMonth} ${year}`
})

/**
 * Get screening status
 */
function getScreeningStatus(screening) {
  const startTime = new Date(screening.start_time)
  const endTime = new Date(screening.end_time)
  const now = new Date()
  
  if (now < startTime) return 'upcoming'
  if (now >= startTime && now <= endTime) return 'playing'
  return 'completed'
}
</script>

<template>
  <div class="space-y-4">
    <!-- Calendar Navigation -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <Button variant="outline" size="icon" @click="previousWeek">
          <ChevronLeft class="h-4 w-4" />
        </Button>
        <Button variant="outline" @click="goToToday">
          <CalendarDays class="mr-2 h-4 w-4" />
          Today
        </Button>
        <Button variant="outline" size="icon" @click="nextWeek">
          <ChevronRight class="h-4 w-4" />
        </Button>
        <span class="ml-2 font-medium">{{ weekRangeDisplay }}</span>
      </div>
      
      <!-- Room Filter -->
      <div class="flex items-center gap-2">
        <span class="text-sm text-muted-foreground">Filter by room:</span>
        <Select v-model="selectedRoomId">
          <SelectTrigger class="w-[180px]">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">All Rooms</SelectItem>
            <SelectItem v-for="room in rooms" :key="room.id" :value="room.id.toString()">
              {{ room.name }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <!-- Week Grid -->
    <div class="grid grid-cols-7 gap-2">
      <Card
        v-for="date in weekDates"
        :key="date.toISOString()"
        :class="[
          'min-h-[300px]',
          isToday(date) && 'ring-2 ring-primary'
        ]"
      >
        <CardHeader class="pb-2 px-3 pt-3">
          <CardTitle 
            class="text-sm font-medium"
            :class="isToday(date) ? 'text-primary' : ''"
          >
            {{ formatWeekDate(date) }}
          </CardTitle>
        </CardHeader>
        <CardContent class="px-2 pb-2">
          <ScrollArea class="h-[250px]">
            <div class="space-y-2 pr-2">
              <div
                v-for="screening in getScreeningsForDate(date)"
                :key="screening.id"
                class="rounded-lg border p-2 text-xs transition-colors hover:bg-muted/50 group cursor-pointer"
                :class="{
                  'border-green-500/50 bg-green-500/10': getScreeningStatus(screening) === 'upcoming',
                  'border-primary/50 bg-primary/10': getScreeningStatus(screening) === 'playing',
                  'border-muted': getScreeningStatus(screening) === 'completed'
                }"
              >
                <!-- Time -->
                <div class="flex items-center gap-1 text-muted-foreground mb-1">
                  <Clock class="h-3 w-3" />
                  {{ formatTime(screening.start_time) }} - {{ formatTime(screening.end_time) }}
                </div>
                
                <!-- Movie -->
                <div class="font-medium truncate mb-1">
                  {{ getMovie(screening.movies_id)?.title || 'Unknown' }}
                </div>
                
                <!-- Room -->
                <div class="flex items-center gap-1 text-muted-foreground">
                  <DoorOpen class="h-3 w-3" />
                  {{ getRoom(screening.room_id)?.name || 'Unknown' }}
                </div>
                
                <!-- Actions (show on hover) -->
                <div class="hidden group-hover:flex items-center gap-1 mt-2 pt-2 border-t">
                  <Button 
                    variant="ghost" 
                    size="sm" 
                    class="h-6 px-2 text-xs"
                    @click.stop="emit('edit', screening)"
                  >
                    <Pencil class="h-3 w-3 mr-1" />
                    Edit
                  </Button>
                  <Button 
                    variant="ghost" 
                    size="sm" 
                    class="h-6 px-2 text-xs text-destructive hover:text-destructive"
                    @click.stop="emit('delete', screening)"
                  >
                    <Trash2 class="h-3 w-3" />
                  </Button>
                </div>
              </div>
              
              <!-- Empty state for this day -->
              <div 
                v-if="!getScreeningsForDate(date).length"
                class="flex flex-col items-center justify-center h-full min-h-[100px] text-muted-foreground text-xs text-center"
              >
                <Film class="h-6 w-6 mb-2 opacity-50" />
                <p>No screenings</p>
              </div>
            </div>
          </ScrollArea>
        </CardContent>
      </Card>
    </div>

    <!-- Legend -->
    <div class="flex items-center gap-4 text-xs text-muted-foreground">
      <div class="flex items-center gap-2">
        <div class="h-3 w-3 rounded-full bg-green-500/50" />
        <span>Upcoming</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="h-3 w-3 rounded-full bg-primary/50" />
        <span>Now Playing</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="h-3 w-3 rounded-full bg-muted" />
        <span>Completed</span>
      </div>
    </div>
  </div>
</template>
