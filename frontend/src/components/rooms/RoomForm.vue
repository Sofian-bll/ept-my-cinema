<script setup>
import { ref, computed, watch } from 'vue'
import { useRooms } from '@/composables'
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
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  room: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:open', 'submit'])

const { roomTypes } = useRooms()

const isOpen = computed({
  get: () => props.open,
  set: (value) => emit('update:open', value)
})

const isEditing = computed(() => !!props.room?.id)

const form = ref({
  name: '',
  capacity: '',
  type: ''
})

const errors = ref({})

// Reset form when dialog opens/closes or room changes
watch(() => [props.open, props.room], ([open, room]) => {
  if (open && room) {
    form.value = {
      name: room.name || '',
      capacity: room.capacity?.toString() || '',
      type: room.type || 'Standard'
    }
  } else if (open && !room) {
    form.value = {
      name: '',
      capacity: '',
      type: 'Standard'
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
  
  if (!form.value.name.trim()) {
    errors.value.name = 'Name is required'
  }
  
  if (!form.value.capacity) {
    errors.value.capacity = 'Capacity is required'
  } else if (parseInt(form.value.capacity) <= 0) {
    errors.value.capacity = 'Capacity must be positive'
  }
  
  return Object.keys(errors.value).length === 0
}

/**
 * Handle form submission
 */
function handleSubmit() {
  if (!validate()) return
  
  const data = {
    name: form.value.name.trim(),
    capacity: parseInt(form.value.capacity),
    type: form.value.type || null
  }
  
  if (isEditing.value) {
    data.id = props.room.id
  }
  
  emit('submit', data)
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>
          {{ isEditing ? 'Edit Room' : 'Add New Room' }}
        </DialogTitle>
        <DialogDescription>
          {{ isEditing ? 'Update the room details below.' : 'Fill in the details for the new room.' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Name -->
        <div class="space-y-2">
          <Label for="name">Name *</Label>
          <Input
            id="name"
            v-model="form.name"
            placeholder="e.g., Salle 1"
            :class="{ 'border-destructive': errors.name }"
          />
          <p v-if="errors.name" class="text-sm text-destructive">{{ errors.name }}</p>
        </div>

        <!-- Capacity -->
        <div class="space-y-2">
          <Label for="capacity">Capacity (seats) *</Label>
          <Input
            id="capacity"
            v-model="form.capacity"
            type="number"
            min="1"
            placeholder="100"
            :class="{ 'border-destructive': errors.capacity }"
          />
          <p v-if="errors.capacity" class="text-sm text-destructive">{{ errors.capacity }}</p>
        </div>

        <!-- Type -->
        <div class="space-y-2">
          <Label for="type">Room Type</Label>
          <Select v-model="form.type">
            <SelectTrigger>
              <SelectValue placeholder="Select room type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="type in roomTypes" :key="type" :value="type">
                {{ type }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="isOpen = false">
            Cancel
          </Button>
          <Button type="submit" :disabled="loading">
            <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
            {{ isEditing ? 'Save Changes' : 'Add Room' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
