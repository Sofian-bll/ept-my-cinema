<script setup>
import { ref, inject, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import { Plus, Pencil, Trash2, MoreHorizontal } from 'lucide-vue-next'
import { screeningsApi, moviesApi, roomsApi } from '@/services/api.js'
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
import ScreeningForm from './ScreeningForm.vue'

const screenings = ref([])
const movies = ref([])
const rooms = ref([])
const loading = ref(false)
const formOpen = ref(false)
const editingScreening = ref(null)
const deleteTarget = ref(null)
const refreshStats = inject('refreshStats')

async function fetchAll() {
  loading.value = true
  try {
    const [s, m, r] = await Promise.all([
      screeningsApi.list(),
      moviesApi.list(),
      roomsApi.list(),
    ])
    screenings.value = s
    movies.value = m
    rooms.value = r
  } catch {
    toast.error('Erreur lors du chargement des seances')
  } finally {
    loading.value = false
  }
}

function getMovieTitle(id) {
  return movies.value.find((m) => m.id === id)?.title || `Film #${id}`
}

function getRoomName(id) {
  return rooms.value.find((r) => r.id === id)?.name || `Salle #${id}`
}

function formatDate(datetime) {
  if (!datetime) return '-'
  const d = new Date(datetime)
  return d.toLocaleString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

function openCreate() {
  editingScreening.value = null
  formOpen.value = true
}

function openEdit(screening) {
  editingScreening.value = { ...screening }
  formOpen.value = true
}

async function handleSave() {
  await fetchAll()
  refreshStats()
}

async function confirmDelete() {
  if (!deleteTarget.value) return
  try {
    await screeningsApi.delete(deleteTarget.value.id)
    toast.success('Seance supprimee')
    deleteTarget.value = null
    await fetchAll()
    refreshStats()
  } catch (err) {
    toast.error(err.message)
    deleteTarget.value = null
  }
}

onMounted(fetchAll)
</script>

<template>
  <div>
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Seances</h2>
      <Button @click="openCreate">
        <Plus class="mr-2 size-4" />
        Ajouter une seance
      </Button>
    </div>

    <div v-if="loading" class="py-8 text-center text-muted-foreground">
      Chargement...
    </div>

    <div v-else-if="!screenings.length" class="py-8 text-center text-muted-foreground">
      Aucune seance
    </div>

    <Table v-else>
      <TableHeader>
        <TableRow>
          <TableHead>Film</TableHead>
          <TableHead>Salle</TableHead>
          <TableHead>Debut</TableHead>
          <TableHead>Fin</TableHead>
          <TableHead class="w-12" />
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="screening in screenings" :key="screening.id">
          <TableCell class="font-medium">{{ getMovieTitle(screening.movies_id) }}</TableCell>
          <TableCell>
            <Badge variant="outline">{{ getRoomName(screening.room_id) }}</Badge>
          </TableCell>
          <TableCell>{{ formatDate(screening.start_time) }}</TableCell>
          <TableCell>{{ formatDate(screening.end_time) }}</TableCell>
          <TableCell>
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon-sm">
                  <MoreHorizontal class="size-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="openEdit(screening)">
                  <Pencil class="mr-2 size-4" />
                  Modifier
                </DropdownMenuItem>
                <DropdownMenuItem
                  class="text-destructive"
                  @click="deleteTarget = screening"
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

    <ScreeningForm
      v-model:open="formOpen"
      :screening="editingScreening"
      :movies="movies"
      :rooms="rooms"
      @saved="handleSave"
    />

    <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Supprimer la seance</AlertDialogTitle>
          <AlertDialogDescription>
            Supprimer cette seance ? Cette action est irreversible.
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
