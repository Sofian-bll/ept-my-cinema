<script setup>
import { ref, watch } from 'vue'
import { toast } from 'vue-sonner'
import { moviesApi } from '@/services/api.js'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Dialog, DialogContent, DialogDescription, DialogFooter,
  DialogHeader, DialogTitle,
} from '@/components/ui/dialog'

const props = defineProps({
  open: { type: Boolean, required: true },
  movie: { type: Object, default: null },
})

const emit = defineEmits(['update:open', 'saved'])

const saving = ref(false)
const form = ref(emptyForm())

function emptyForm() {
  return { title: '', description: '', duration: '', release_year: '', genre: '', director: '' }
}

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    form.value = props.movie
      ? { ...props.movie, duration: String(props.movie.duration), release_year: String(props.movie.release_year) }
      : emptyForm()
  }
})

const isEdit = () => !!props.movie

async function handleSubmit() {
  saving.value = true
  try {
    const body = {
      ...form.value,
      duration: Number(form.value.duration),
      release_year: Number(form.value.release_year),
    }

    if (isEdit()) {
      await moviesApi.update(props.movie.id, body)
      toast.success(`"${body.title}" modifie`)
    } else {
      await moviesApi.create(body)
      toast.success(`"${body.title}" ajoute`)
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
        <DialogTitle>{{ isEdit() ? 'Modifier le film' : 'Ajouter un film' }}</DialogTitle>
        <DialogDescription>
          {{ isEdit() ? 'Modifiez les informations du film.' : 'Remplissez les informations du nouveau film.' }}
        </DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="handleSubmit">
        <div class="space-y-2">
          <Label for="title">Titre *</Label>
          <Input id="title" v-model="form.title" placeholder="Titre du film" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="duration">Duree (min) *</Label>
            <Input id="duration" v-model="form.duration" type="number" placeholder="120" required />
          </div>
          <div class="space-y-2">
            <Label for="release_year">Annee *</Label>
            <Input id="release_year" v-model="form.release_year" type="number" placeholder="2024" required />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="genre">Genre</Label>
            <Input id="genre" v-model="form.genre" placeholder="Action, Drame..." />
          </div>
          <div class="space-y-2">
            <Label for="director">Realisateur</Label>
            <Input id="director" v-model="form.director" placeholder="Nom du realisateur" />
          </div>
        </div>

        <div class="space-y-2">
          <Label for="description">Description</Label>
          <Input id="description" v-model="form.description" placeholder="Synopsis du film" />
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
