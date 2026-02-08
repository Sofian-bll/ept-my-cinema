<script setup>
import { ref, inject, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import { Plus, Pencil, Trash2, MoreHorizontal } from 'lucide-vue-next'
import { moviesApi } from '@/services/api.js'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
  Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import {
  DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
  AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import MovieForm from './MovieForm.vue'

const movies = ref([])
const loading = ref(false)
const formOpen = ref(false)
const editingMovie = ref(null)
const deleteTarget = ref(null)
const refreshStats = inject('refreshStats')

async function fetchMovies() {
  loading.value = true
  try {
    movies.value = await moviesApi.list()
  } catch {
    toast.error('Erreur lors du chargement des films')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingMovie.value = null
  formOpen.value = true
}

function openEdit(movie) {
  editingMovie.value = { ...movie }
  formOpen.value = true
}

async function handleSave() {
  await fetchMovies()
  refreshStats()
}

async function confirmDelete() {
  if (!deleteTarget.value) return
  try {
    await moviesApi.delete(deleteTarget.value.id)
    toast.success(`"${deleteTarget.value.title}" supprime`)
    deleteTarget.value = null
    await fetchMovies()
    refreshStats()
  } catch (err) {
    if (err.status === 409) {
      toast.error('Impossible de supprimer : des seances sont liees a ce film')
    } else {
      toast.error(err.message)
    }
    deleteTarget.value = null
  }
}

onMounted(fetchMovies)
</script>

<template>
  <div>
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Films</h2>
      <Button @click="openCreate">
        <Plus class="mr-2 size-4" />
        Ajouter un film
      </Button>
    </div>

    <div v-if="loading" class="py-8 text-center text-muted-foreground">
      Chargement...
    </div>

    <div v-else-if="!movies.length" class="py-8 text-center text-muted-foreground">
      Aucun film
    </div>

    <Table v-else>
      <TableHeader>
        <TableRow>
          <TableHead>Titre</TableHead>
          <TableHead>Genre</TableHead>
          <TableHead>Realisateur</TableHead>
          <TableHead class="text-right">Duree (min)</TableHead>
          <TableHead class="text-right">Annee</TableHead>
          <TableHead class="w-12" />
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="movie in movies" :key="movie.id">
          <TableCell class="font-medium">{{ movie.title }}</TableCell>
          <TableCell>
            <Badge v-if="movie.genre" variant="secondary">{{ movie.genre }}</Badge>
          </TableCell>
          <TableCell>{{ movie.director || '-' }}</TableCell>
          <TableCell class="text-right">{{ movie.duration }}</TableCell>
          <TableCell class="text-right">{{ movie.release_year }}</TableCell>
          <TableCell>
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon-sm">
                  <MoreHorizontal class="size-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="openEdit(movie)">
                  <Pencil class="mr-2 size-4" />
                  Modifier
                </DropdownMenuItem>
                <DropdownMenuItem
                  class="text-destructive"
                  @click="deleteTarget = movie"
                >
                  <Trash2 class="mr-2 size-4" />
                  Supprimer
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <MovieForm
      v-model:open="formOpen"
      :movie="editingMovie"
      @saved="handleSave"
    />

    <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Supprimer le film</AlertDialogTitle>
          <AlertDialogDescription>
            Supprimer "{{ deleteTarget?.title }}" ? Cette action est irreversible.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Annuler</AlertDialogCancel>
          <AlertDialogAction variant="destructive" @click="confirmDelete">
            Supprimer
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>
