<script setup>
import { ref, computed, watch } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle
} from '@/components/ui/dialog'
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  movie: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:open', 'submit'])

const isOpen = computed({
  get: () => props.open,
  set: (value) => emit('update:open', value)
})

const isEditing = computed(() => !!props.movie?.id)

const form = ref({
  title: '',
  description: '',
  duration: '',
  release_year: '',
  genre: '',
  director: ''
})

const errors = ref({})

watch(() => [props.open, props.movie], ([open, movie]) => {
  if (open && movie) {
    form.value = {
      title: movie.title || '',
      description: movie.description || '',
      duration: movie.duration?.toString() || '',
      release_year: movie.release_year?.toString() || '',
      genre: movie.genre || '',
      director: movie.director || ''
    }
  } else if (open && !movie) {
    form.value = {
      title: '',
      description: '',
      duration: '',
      release_year: new Date().getFullYear().toString(),
      genre: '',
      director: ''
    }
  }
  errors.value = {}
}, { immediate: true })

function validate() {
  errors.value = {}
  
  if (!form.value.title.trim()) {
    errors.value.title = 'Title is required'
  }
  
  if (!form.value.duration) {
    errors.value.duration = 'Duration is required'
  } else if (parseInt(form.value.duration) <= 0) {
    errors.value.duration = 'Duration must be positive'
  }
  
  if (!form.value.release_year) {
    errors.value.release_year = 'Release year is required'
  } else {
    const year = parseInt(form.value.release_year)
    if (year < 1888 || year > new Date().getFullYear() + 5) {
      errors.value.release_year = 'Invalid release year'
    }
  }
  
  return Object.keys(errors.value).length === 0
}

function handleSubmit() {
  if (!validate()) return
  
  const data = {
    title: form.value.title.trim(),
    description: form.value.description.trim() || null,
    duration: parseInt(form.value.duration),
    release_year: parseInt(form.value.release_year),
    genre: form.value.genre.trim() || null,
    director: form.value.director.trim() || null
  }
  
  if (isEditing.value) {
    data.id = props.movie.id
  }
  
  emit('submit', data)
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogContent class="sm:max-w-[500px]">
      <DialogHeader>
        <DialogTitle>
          {{ isEditing ? 'Edit Movie' : 'Add New Movie' }}
        </DialogTitle>
        <DialogDescription>
          {{ isEditing ? 'Update the movie details below.' : 'Fill in the details for the new movie.' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="space-y-2">
          <Label for="title">Title *</Label>
          <Input
            id="title"
            v-model="form.title"
            placeholder="Enter movie title"
            :class="{ 'border-destructive': errors.title }"
          />
          <p v-if="errors.title" class="text-sm text-destructive">{{ errors.title }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="duration">Duration (minutes) *</Label>
            <Input
              id="duration"
              v-model="form.duration"
              type="number"
              min="1"
              placeholder="120"
              :class="{ 'border-destructive': errors.duration }"
            />
            <p v-if="errors.duration" class="text-sm text-destructive">{{ errors.duration }}</p>
          </div>
          
          <div class="space-y-2">
            <Label for="release_year">Release Year *</Label>
            <Input
              id="release_year"
              v-model="form.release_year"
              type="number"
              min="1888"
              :max="new Date().getFullYear() + 5"
              placeholder="2024"
              :class="{ 'border-destructive': errors.release_year }"
            />
            <p v-if="errors.release_year" class="text-sm text-destructive">{{ errors.release_year }}</p>
          </div>
        </div>

        <div class="space-y-2">
          <Label for="genre">Genre</Label>
          <Input
            id="genre"
            v-model="form.genre"
            placeholder="Action, Drama, Sci-Fi"
          />
          <p class="text-xs text-muted-foreground">Separate multiple genres with commas</p>
        </div>

        <div class="space-y-2">
          <Label for="director">Director</Label>
          <Input
            id="director"
            v-model="form.director"
            placeholder="Director name"
          />
        </div>

        <div class="space-y-2">
          <Label for="description">Description</Label>
          <Textarea
            id="description"
            v-model="form.description"
            placeholder="Movie description..."
            rows="3"
          />
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="isOpen = false">
            Cancel
          </Button>
          <Button type="submit" :disabled="loading">
            <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
            {{ isEditing ? 'Save Changes' : 'Add Movie' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
