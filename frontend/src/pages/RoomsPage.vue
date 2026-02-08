<script setup>
import { ref, onMounted } from 'vue'
import { useRooms } from '@/composables'
import { AppLayout } from '@/components/layout'
import { RoomTable, RoomForm } from '@/components/rooms'
import { Button } from '@/components/ui/button'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle
} from '@/components/ui/alert-dialog'
import { Toaster } from '@/components/ui/sonner'
import { toast } from 'vue-sonner'
import { Plus, DoorOpen } from 'lucide-vue-next'

const { rooms, loading, error, fetchAll, create, update, remove } = useRooms()

const formOpen = ref(false)
const deleteDialogOpen = ref(false)
const selectedRoom = ref(null)
const formLoading = ref(false)

/**
 * Open form for creating new room
 */
function handleAdd() {
  selectedRoom.value = null
  formOpen.value = true
}

/**
 * Open form for editing room
 */
function handleEdit(room) {
  selectedRoom.value = room
  formOpen.value = true
}

/**
 * Open delete confirmation
 */
function handleDelete(room) {
  selectedRoom.value = room
  deleteDialogOpen.value = true
}

/**
 * View room screenings
 */
function handleView(room) {
  selectedRoom.value = room
  toast.info(`${room.name}`, {
    description: `Capacity: ${room.capacity} seats • Type: ${room.type || 'Standard'}`
  })
}

/**
 * Submit room form (create or update)
 */
async function handleSubmit(data) {
  formLoading.value = true
  try {
    if (data.id) {
      await update(data.id, data)
      toast.success('Room updated', {
        description: `"${data.name}" has been updated successfully.`
      })
    } else {
      await create(data)
      toast.success('Room added', {
        description: `"${data.name}" has been added.`
      })
    }
    formOpen.value = false
  } catch (err) {
    toast.error('Error', {
      description: err.message || 'Failed to save room'
    })
  } finally {
    formLoading.value = false
  }
}

/**
 * Confirm delete room
 */
async function confirmDelete() {
  if (!selectedRoom.value) return
  
  try {
    await remove(selectedRoom.value.id)
    toast.success('Room deleted', {
      description: `"${selectedRoom.value.name}" has been deactivated.`
    })
    deleteDialogOpen.value = false
  } catch (err) {
    toast.error('Cannot delete room', {
      description: err.message || 'This room may have associated screenings.'
    })
  }
}

onMounted(() => {
  fetchAll()
})
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight flex items-center gap-2">
            <DoorOpen class="h-8 w-8 text-primary" />
            Rooms
          </h1>
          <p class="text-muted-foreground">
            Manage your cinema rooms and their configurations
          </p>
        </div>
        <Button @click="handleAdd">
          <Plus class="mr-2 h-4 w-4" />
          Add Room
        </Button>
      </div>

      <!-- Rooms Table -->
      <RoomTable
        :data="rooms"
        :loading="loading"
        @edit="handleEdit"
        @delete="handleDelete"
        @view="handleView"
      />

      <!-- Room Form Dialog -->
      <RoomForm
        v-model:open="formOpen"
        :room="selectedRoom"
        :loading="formLoading"
        @submit="handleSubmit"
      />

      <!-- Delete Confirmation Dialog -->
      <AlertDialog v-model:open="deleteDialogOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Room</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete "{{ selectedRoom?.name }}"?
              <br /><br />
              The room will be deactivated and no longer available for new screenings.
              Existing screenings will remain scheduled.
              <br /><br />
              <strong class="text-amber-500">Note:</strong> Rooms with active screenings cannot be deleted.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction 
              class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
              @click="confirmDelete"
            >
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>

    <Toaster position="top-right" richColors />
  </AppLayout>
</template>
