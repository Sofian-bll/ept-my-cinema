<script setup>
import { ref, onMounted } from 'vue'
import { useMovies } from '@/composables'
import { AppLayout } from '@/components/layout'
import { MovieTable, MovieForm } from '@/components/movies'
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
import { Plus, Film } from 'lucide-vue-next'

const { movies, loading, error, fetchAll, create, update, remove } = useMovies()

const formOpen = ref(false)
const deleteDialogOpen = ref(false)
const selectedMovie = ref(null)
const formLoading = ref(false)

/**
 * Open form for creating new movie
 */
function handleAdd() {
  selectedMovie.value = null
  formOpen.value = true
}

/**
 * Open form for editing movie
 */
function handleEdit(movie) {
  selectedMovie.value = movie
  formOpen.value = true
}

/**
 * Open delete confirmation
 */
function handleDelete(movie) {
  selectedMovie.value = movie
  deleteDialogOpen.value = true
}

/**
 * View movie details
 */
function handleView(movie) {
  selectedMovie.value = movie
  // Could open a detail modal here
  toast.info(`Viewing: ${movie.title}`, {
    description: `${movie.release_year} • ${movie.duration} minutes`
  })
}

/**
 * Submit movie form (create or update)
 */
async function handleSubmit(data) {
  formLoading.value = true
  try {
    if (data.id) {
      await update(data.id, data)
      toast.success('Movie updated', {
        description: `"${data.title}" has been updated successfully.`
      })
    } else {
      await create(data)
      toast.success('Movie added', {
        description: `"${data.title}" has been added to the catalog.`
      })
    }
    formOpen.value = false
  } catch (err) {
    toast.error('Error', {
      description: err.message || 'Failed to save movie'
    })
  } finally {
    formLoading.value = false
  }
}

/**
 * Confirm delete movie
 */
async function confirmDelete() {
  if (!selectedMovie.value) return
  
  try {
    await remove(selectedMovie.value.id)
    toast.success('Movie deleted', {
      description: `"${selectedMovie.value.title}" has been removed.`
    })
    deleteDialogOpen.value = false
  } catch (err) {
    toast.error('Cannot delete movie', {
      description: err.message || 'This movie may have associated screenings.'
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
            <Film class="h-8 w-8 text-primary" />
            Movies
          </h1>
          <p class="text-muted-foreground">
            Manage your cinema's movie catalog
          </p>
        </div>
        <Button @click="handleAdd">
          <Plus class="mr-2 h-4 w-4" />
          Add Movie
        </Button>
      </div>

      <!-- Movies Table -->
      <MovieTable
        :data="movies"
        :loading="loading"
        @edit="handleEdit"
        @delete="handleDelete"
        @view="handleView"
      />

      <!-- Movie Form Dialog -->
      <MovieForm
        v-model:open="formOpen"
        :movie="selectedMovie"
        :loading="formLoading"
        @submit="handleSubmit"
      />

      <!-- Delete Confirmation Dialog -->
      <AlertDialog v-model:open="deleteDialogOpen">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Movie</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete "{{ selectedMovie?.title }}"?
              This action cannot be undone.
              <br /><br />
              <strong class="text-amber-500">Note:</strong> Movies with active screenings cannot be deleted.
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
