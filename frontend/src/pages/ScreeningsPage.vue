<script setup>
import { ref, onMounted } from 'vue'
import { useMovies, useRooms, useScreenings } from '@/composables'
import { AppLayout } from '@/components/layout'
import { ScreeningTable, ScreeningForm, ScreeningCalendar } from '@/components/screenings'
import { Button } from '@/components/ui/button'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
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
import { Plus, CalendarClock, List, CalendarDays } from 'lucide-vue-next'

const { movies, fetchAll: fetchMovies } = useMovies()
const { rooms, fetchAll: fetchRooms } = useRooms()
const { screenings, loading, fetchAll: fetchScreenings, create, update, remove } = useScreenings()

const formOpen = ref(false)
const deleteDialogOpen = ref(false)
const selectedScreening = ref(null)
const formLoading = ref(false)
const activeTab = ref('table')

/**
 * Open form for creating new screening
 */
function handleAdd() {
  selectedScreening.value = null
  formOpen.value = true
}

/**
 * Open form for editing screening
 */
function handleEdit(screening) {
  selectedScreening.value = screening
  formOpen.value = true
}

/**
 * Open delete confirmation
 */
function handleDelete(screening) {
  selectedScreening.value = screening
  deleteDialogOpen.value = true
}

/**
 * Get movie title by ID
 */
function getMovieTitle(movieId) {
  const movie = movies.value.find(m => m.id === movieId)
  return movie?.title || 'Unknown Movie'
}

/**
 * Submit screening form (create or update)
 */
async function handleSubmit(data) {
  formLoading.value = true
  try {
    if (data.id) {
      await update(data.id, data)
      toast.success('Screening updated', {
        description: 'The screening has been updated successfully.'
      })
    } else {
      await create(data)
      toast.success('Screening scheduled', {
        description: 'The new screening has been added to the schedule.'
      })
    }
    formOpen.value = false
  } catch (err) {
    // Handle overlap error specifically
    if (err.status === 409) {
      toast.error('Scheduling Conflict', {
        description: 'This time slot conflicts with an existing screening in this room. Please choose a different time.',
        duration: 5000
      })
    } else {
      toast.error('Error', {
        description: err.message || 'Failed to save screening'
      })
    }
  } finally {
    formLoading.value = false
  }
}

/**
 * Confirm delete screening
 */
async function confirmDelete() {
  if (!selectedScreening.value) return
  
  try {
    await remove(selectedScreening.value.id)
    toast.success('Screening deleted', {
      description: 'The screening has been removed from the schedule.'
    })
    deleteDialogOpen.value = false
  } catch (err) {
    toast.error('Error', {
      description: err.message || 'Failed to delete screening'
    })
  }
}

onMounted(async () => {
  await Promise.all([
    fetchMovies(),
    fetchRooms(),
    fetchScreenings()
  ])
})
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight flex items-center gap-2">
            <CalendarClock class="h-8 w-8 text-primary" />
            Screenings
          </h1>
          <p class="text-muted-foreground">
            Schedule and manage movie screenings
          </p>
        </div>
        <Button @click="handleAdd">
          <Plus class="mr-2 h-4 w-4" />
          Schedule Screening
        </Button>
      </div>

      <!-- View Tabs -->
      <Tabs v-model="activeTab" class="w-full">
        <TabsList>
          <TabsTrigger value="table">
            <List class="mr-2 h-4 w-4" />
            Table View
          </TabsTrigger>
          <TabsTrigger value="calendar">
            <CalendarDays class="mr-2 h-4 w-4" />
            Calendar View
          </TabsTrigger>
        </TabsList>

        <TabsContent value="table" class="mt-4">
          <ScreeningTable
            :data="screenings"
            :movies="movies"
            :rooms="rooms"
            :loading="loading"
            @edit="handleEdit"
            @delete="handleDelete"
          />
        </TabsContent>

        <TabsContent value="calendar" class="mt-4">
          <ScreeningCalendar
            :screenings="screenings"
            :movies="movies"
            :rooms="rooms"
            :loading="loading"
            @edit="handleEdit"
            @delete="handleDelete"
            @add="handleAdd"
          />
        </TabsContent>
      </Tabs>

      <!-- Screening Form Dialog -->
      <ScreeningForm
        v-model:open="formOpen"
        :screening="selectedScreening"
        :movies="movies"
        :rooms="rooms"
        :loading="formLoading"
        @submit="handleSubmit"
      />

      <!-- Delete Confirmation Dialog -->
      <AlertDialog v-model:open="deleteDialogOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Screening</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete this screening of "{{ getMovieTitle(selectedScreening?.movies_id) }}"?
              <br /><br />
              This action cannot be undone.
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
