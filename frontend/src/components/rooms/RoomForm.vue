<script setup>
import { ref, watch } from 'vue'
import { toast } from 'vue-sonner'
import { roomsApi } from '@/services/api.js'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Dialog, DialogContent, DialogDescription, DialogFooter,
  DialogHeader, DialogTitle,
} from '@/components/ui/dialog'

const props = defineProps({
  open: { type: Boolean, required: true },
  room: { type: Object, default: null },
})

const emit = defineEmits(['update:open', 'saved'])

const saving = ref(false)
const form = ref(emptyForm())

function emptyForm() {
  return { name: '', capacity: '', type: '' }
}

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    form.value = props.room
      ? { ...props.room, capacity: String(props.room.capacity) }
      : emptyForm()
  }
})

const isEdit = () => !!props.room

async function handleSubmit() {
  saving.value = true
  try {
    const body = {
      ...form.value,
      capacity: Number(form.value.capacity),
    }

    if (isEdit()) {
      await roomsApi.update(props.room.id, body)
      toast.success(`"${body.name}" modifiee`)
    } else {
      await roomsApi.create(body)
      toast.success(`"${body.name}" ajoutee`)
    }

    emit('update:open', false)
    emit('saved')
  } catch (err) {
    if (err.status === 400 && err.data?.data) {
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
        <DialogTitle>{{ isEdit() ? 'Modifier la salle' : 'Ajouter une salle' }}</DialogTitle>
        <DialogDescription>
          {{ isEdit() ? 'Modifiez les informations de la salle.' : 'Remplissez les informations de la nouvelle salle.' }}
        </DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="handleSubmit">
        <div class="space-y-2">
          <Label for="name">Nom *</Label>
          <Input id="name" v-model="form.name" placeholder="Salle 1" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="capacity">Capacite *</Label>
            <Input id="capacity" v-model="form.capacity" type="number" placeholder="150" required />
          </div>
          <div class="space-y-2">
            <Label for="type">Type</Label>
            <Input id="type" v-model="form.type" placeholder="Standard, IMAX, VIP..." />
          </div>
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
