<script setup>
import { ref, inject, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import { Plus, Pencil, Trash2, MoreHorizontal } from 'lucide-vue-next'
import { roomsApi } from '@/services/api.js'
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
import RoomForm from './RoomForm.vue'

const rooms = ref([])
const loading = ref(false)
const formOpen = ref(false)
const editingRoom = ref(null)
const deleteTarget = ref(null)
const refreshStats = inject('refreshStats')

async function fetchRooms() {
  loading.value = true
  try {
    rooms.value = await roomsApi.list()
  } catch {
    toast.error('Erreur lors du chargement des salles')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingRoom.value = null
  formOpen.value = true
}

function openEdit(room) {
  editingRoom.value = { ...room }
  formOpen.value = true
}

async function handleSave() {
  await fetchRooms()
  refreshStats()
}

async function confirmDelete() {
  if (!deleteTarget.value) return
  try {
    await roomsApi.delete(deleteTarget.value.id)
    toast.success(`"${deleteTarget.value.name}" desactivee`)
    deleteTarget.value = null
    await fetchRooms()
    refreshStats()
  } catch (err) {
    if (err.status === 409) {
      toast.error('Impossible : des seances sont liees a cette salle')
    } else if (err.status === 410) {
      toast.error('Cette salle est deja inactive')
    } else {
      toast.error(err.message)
    }
    deleteTarget.value = null
  }
}

const typeBadgeVariant = (type) => {
  const variants = {
    'IMAX': 'default',
    'VIP': 'default',
    'Premium': 'secondary',
    '4DX': 'default',
    'Dolby Atmos': 'secondary',
  }
  return variants[type] || 'outline'
}

onMounted(fetchRooms)
</script>

<template>
  <div>
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Salles</h2>
      <Button @click="openCreate">
        <Plus class="mr-2 size-4" />
        Ajouter une salle
      </Button>
    </div>

    <div v-if="loading" class="py-8 text-center text-muted-foreground">
      Chargement...
    </div>

    <div v-else-if="!rooms.length" class="py-8 text-center text-muted-foreground">
      Aucune salle
    </div>

    <Table v-else>
      <TableHeader>
        <TableRow>
          <TableHead>Nom</TableHead>
          <TableHead>Type</TableHead>
          <TableHead class="text-right">Capacite</TableHead>
          <TableHead class="w-12" />
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="room in rooms" :key="room.id">
          <TableCell class="font-medium">{{ room.name }}</TableCell>
          <TableCell>
            <Badge v-if="room.type" :variant="typeBadgeVariant(room.type)">
              {{ room.type }}
            </Badge>
          </TableCell>
          <TableCell class="text-right">{{ room.capacity }} places</TableCell>
          <TableCell>
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon-sm">
                  <MoreHorizontal class="size-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="openEdit(room)">
                  <Pencil class="mr-2 size-4" />
                  Modifier
                </DropdownMenuItem>
                <DropdownMenuItem
                  class="text-destructive"
                  @click="deleteTarget = room"
                >
                  <Trash2 class="mr-2 size-4" />
                  Desactiver
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <RoomForm
      v-model:open="formOpen"
      :room="editingRoom"
      @saved="handleSave"
    />

    <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Desactiver la salle</AlertDialogTitle>
          <AlertDialogDescription>
            Desactiver "{{ deleteTarget?.name }}" ? La salle ne sera plus visible dans la liste.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Annuler</AlertDialogCancel>
          <AlertDialogAction variant="destructive" @click="confirmDelete">
            Desactiver
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>
