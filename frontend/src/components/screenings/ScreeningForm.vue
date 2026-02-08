<script setup>
import { ref, watch } from 'vue'
import { toast } from 'vue-sonner'
import { screeningsApi } from '@/services/api.js'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select'
import {
  Dialog, DialogContent, DialogDescription, DialogFooter,
  DialogHeader, DialogTitle,
} from '@/components/ui/dialog'

const props = defineProps({
  open: { type: Boolean, required: true },
  screening: { type: Object, default: null },
  movies: { type: Array, default: () => [] },
  rooms: { type: Array, default: () => [] },
})

const emit = defineEmits(['update:open', 'saved'])

const saving = ref(false)
const form = ref(emptyForm())

function emptyForm() {
  return { movies_id: '', room_id: '', start_time: '' }
}

function toLocalDatetime(datetime) {
  if (!datetime) return ''
  const d = new Date(datetime)
  const offset = d.getTimezoneOffset()
  const local = new Date(d.getTime() - offset * 60000)
  return local.toISOString().slice(0, 16)
}

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    form.value = props.screening
      ? {
          movies_id: String(props.screening.movies_id),
          room_id: String(props.screening.room_id),
          start_time: toLocalDatetime(props.screening.start_time),
        }
      : emptyForm()
  }
})

const isEdit = () => !!props.screening

async function handleSubmit() {
  saving.value = true
  try {
    const body = {
      movies_id: Number(form.value.movies_id),
      room_id: Number(form.value.room_id),
      start_time: form.value.start_time.replace('T', ' ') + ':00',
    }

    if (isEdit()) {
      await screeningsApi.update(props.screening.id, body)
      toast.success('Seance modifiee')
    } else {
      await screeningsApi.create(body)
      toast.success('Seance ajoutee')
    }

    emit('update:open', false)
    emit('saved')
  } catch (err) {
    if (err.status === 409) {
      toast.error('Conflit horaire : une autre seance occupe deja ce creneau dans cette salle')
    } else if (err.status === 400 && err.data?.data) {
      toast.error(`Champs requis : ${err.data.data.join(', ')}`)
    } else {
      toast.error(err.message)
    }
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="(v) => emit('update:open', v)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ isEdit() ? 'Modifier la seance' : 'Ajouter une seance' }}</DialogTitle>
        <DialogDescription>
          {{ isEdit() ? 'Modifiez les informations de la seance.' : 'Planifiez une nouvelle seance.' }}
        </DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="handleSubmit">
        <div class="space-y-2">
          <Label>Film *</Label>
          <Select v-model="form.movies_id" required>
            <SelectTrigger>
              <SelectValue placeholder="Choisir un film" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="movie in movies"
                :key="movie.id"
                :value="String(movie.id)"
              >
                {{ movie.title }} ({{ movie.duration }}min)
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div class="space-y-2">
          <Label>Salle *</Label>
          <Select v-model="form.room_id" required>
            <SelectTrigger>
              <SelectValue placeholder="Choisir une salle" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="room in rooms"
                :key="room.id"
                :value="String(room.id)"
              >
                {{ room.name }} ({{ room.capacity }} places{{ room.type ? ' - ' + room.type : '' }})
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div class="space-y-2">
          <Label for="start_time">Date et heure de debut *</Label>
          <Input
            id="start_time"
            v-model="form.start_time"
            type="datetime-local"
            required
          />
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="emit('update:open', false)">
            Annuler
          </Button>
          <Button type="submit" :disabled="saving">
            {{ saving ? 'En cours...' : (isEdit() ? 'Modifier' : 'Ajouter') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
