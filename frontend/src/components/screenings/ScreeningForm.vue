<script setup>
import { ref, computed, watch } from 'vue'
import { formatDuration } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle
} from '@/components/ui/dialog'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from '@/components/ui/select'
import { Badge } from '@/components/ui/badge'
import { Loader2, Clock, AlertCircle } from 'lucide-vue-next'

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  screening: {
    type: Object,
    default: null
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

const emit = defineEmits(['update:open', 'submit'])

const isOpen = computed({
  get: () => props.open,
  set: (value) => emit('update:open', value)
})

const isEditing = computed(() => !!props.screening?.id)

const form = ref({
  movies_id: '',
  room_id: '',
  date: '',
  time: ''
})

const errors = ref({})

/**
 * Get selected movie
 */
const selectedMovie = computed(() => {
  if (!form.value.movies_id) return null
  return props.movies.find(m => m.id.toString() === form.value.movies_id)
})

/**
 * Calculate end time based on start time and movie duration
 */
const calculatedEndTime = computed(() => {
  if (!form.value.date || !form.value.time || !selectedMovie.value) return null
  
  const startDate = new Date(`${form.value.date}T${form.value.time}`)
  const endDate = new Date(startDate.getTime() + selectedMovie.value.duration * 60 * 1000)
  
  return endDate.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  })
})

// Reset form when dialog opens/closes or screening changes
watch(() => [props.open, props.screening], ([open, screening]) => {
  if (open && screening) {
    const startTime = new Date(screening.start_time)
    form.value = {
      movies_id: screening.movies_id?.toString() || '',
      room_id: screening.room_id?.toString() || '',
      date: startTime.toISOString().split('T')[0],
      time: startTime.toTimeString().slice(0, 5)
    }
  } else if (open && !screening) {
    const now = new Date()
    now.setHours(now.getHours() + 1, 0, 0, 0) // Next hour
    form.value = {
      movies_id: '',
      room_id: '',
      date: now.toISOString().split('T')[0],
      time: now.toTimeString().slice(0, 5)
    }
  }
  errors.value = {}
}, { immediate: true })

/**
 * Validate form fields
 * @returns {boolean}
 */
function validate() {
  errors.value = {}
  
  if (!form.value.movies_id) {
    errors.value.movies_id = 'Movie is required'
  }
  
  if (!form.value.room_id) {
    errors.value.room_id = 'Room is required'
  }
  
  if (!form.value.date) {
    errors.value.date = 'Date is required'
  }
  
  if (!form.value.time) {
    errors.value.time = 'Time is required'
  }
  
  return Object.keys(errors.value).length === 0
}

/**
 * Handle form submission
 */
function handleSubmit() {
  if (!validate()) return
  
  const startTime = new Date(`${form.value.date}T${form.value.time}`)
  
  const data = {
    movies_id: parseInt(form.value.movies_id),
    room_id: parseInt(form.value.room_id),
    start_time: startTime.toISOString().slice(0, 19).replace('T', ' ')
  }
  
  if (isEditing.value) {
    data.id = props.screening.id
  }
  
  emit('submit', data)
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogContent class="sm:max-w-[500px]">
      <DialogHeader>
        <DialogTitle>
          {{ isEditing ? 'Edit Screening' : 'Schedule New Screening' }}
        </DialogTitle>
        <DialogDescription>
          {{ isEditing ? 'Update the screening details below.' : 'Select a movie, room, and time for the new screening.' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Movie Selection -->
        <div class="space-y-2">
          <Label for="movie">Movie *</Label>
          <Select v-model="form.movies_id">
            <SelectTrigger :class="{ 'border-destructive': errors.movies_id }">
              <SelectValue placeholder="Select a movie" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem 
                v-for="movie in movies" 
                :key="movie.id" 
                :value="movie.id.toString()"
              >
                <div class="flex items-center justify-between gap-2">
                  <span>{{ movie.title }}</span>
                  <span class="text-xs text-muted-foreground">{{ formatDuration(movie.duration) }}</span>
                </div>
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="errors.movies_id" class="text-sm text-destructive">{{ errors.movies_id }}</p>
          
          <!-- Selected movie info -->
          <div v-if="selectedMovie" class="flex items-center gap-2 text-sm text-muted-foreground">
            <Clock class="h-4 w-4" />
            <span>Duration: {{ formatDuration(selectedMovie.duration) }}</span>
          </div>
        </div>

        <!-- Room Selection -->
        <div class="space-y-2">
          <Label for="room">Room *</Label>
          <Select v-model="form.room_id">
            <SelectTrigger :class="{ 'border-destructive': errors.room_id }">
              <SelectValue placeholder="Select a room" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem 
                v-for="room in rooms" 
                :key="room.id" 
                :value="room.id.toString()"
              >
                <div class="flex items-center gap-2">
                  <span>{{ room.name }}</span>
                  <Badge v-if="room.type" variant="secondary" class="text-xs">
                    {{ room.type }}
                  </Badge>
                </div>
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="errors.room_id" class="text-sm text-destructive">{{ errors.room_id }}</p>
        </div>

        <!-- Date & Time -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="date">Date *</Label>
            <Input
              id="date"
              v-model="form.date"
              type="date"
              :class="{ 'border-destructive': errors.date }"
            />
            <p v-if="errors.date" class="text-sm text-destructive">{{ errors.date }}</p>
          </div>
          
          <div class="space-y-2">
            <Label for="time">Start Time *</Label>
            <Input
              id="time"
              v-model="form.time"
              type="time"
              :class="{ 'border-destructive': errors.time }"
            />
            <p v-if="errors.time" class="text-sm text-destructive">{{ errors.time }}</p>
          </div>
        </div>

        <!-- End Time Preview -->
        <div v-if="calculatedEndTime" class="rounded-lg bg-muted/50 p-3">
          <div class="flex items-center gap-2 text-sm">
            <Clock class="h-4 w-4 text-primary" />
            <span class="font-medium">Screening ends at:</span>
            <span>{{ calculatedEndTime }}</span>
          </div>
        </div>

        <!-- Warning about overlaps -->
        <div class="flex items-start gap-2 rounded-lg border border-amber-500/50 bg-amber-500/10 p-3 text-sm">
          <AlertCircle class="h-4 w-4 text-amber-500 mt-0.5" />
          <p class="text-muted-foreground">
            The system will automatically check for scheduling conflicts. If there's an overlap with another screening, you'll be notified.
          </p>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="isOpen = false">
            Cancel
          </Button>
          <Button type="submit" :disabled="loading">
            <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
            {{ isEditing ? 'Save Changes' : 'Schedule Screening' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
