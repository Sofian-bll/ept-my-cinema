<script setup>
import { formatDuration } from '@/lib/utils'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Pencil, Trash2, Clock, Calendar, User } from 'lucide-vue-next'

const props = defineProps({
  movie: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

/**
 * Get genre badges from comma-separated string
 */
function getGenres(genreString) {
  if (!genreString) return []
  return genreString.split(',').map(g => g.trim()).filter(Boolean)
}
</script>

<template>
  <Card class="overflow-hidden transition-all hover:shadow-lg hover:border-primary/50">
    <CardHeader class="pb-3">
      <div class="flex items-start justify-between gap-2">
        <CardTitle class="text-lg leading-tight">{{ movie.title }}</CardTitle>
        <Badge variant="outline" class="shrink-0">
          {{ movie.release_year }}
        </Badge>
      </div>
      <CardDescription v-if="movie.director" class="flex items-center gap-1">
        <User class="h-3 w-3" />
        {{ movie.director }}
      </CardDescription>
    </CardHeader>

    <CardContent class="space-y-3">
      <!-- Duration -->
      <div class="flex items-center gap-2 text-sm text-muted-foreground">
        <Clock class="h-4 w-4" />
        <span>{{ formatDuration(movie.duration) }}</span>
      </div>

      <!-- Genres -->
      <div v-if="movie.genre" class="flex flex-wrap gap-1">
        <Badge
          v-for="genre in getGenres(movie.genre)"
          :key="genre"
          variant="secondary"
          class="text-xs"
        >
          {{ genre }}
        </Badge>
      </div>

      <!-- Description -->
      <p v-if="movie.description" class="text-sm text-muted-foreground line-clamp-2">
        {{ movie.description }}
      </p>
    </CardContent>

    <CardFooter class="flex gap-2 pt-0">
      <Button variant="outline" size="sm" class="flex-1" @click="emit('edit', movie)">
        <Pencil class="mr-2 h-3 w-3" />
        Edit
      </Button>
      <Button variant="outline" size="sm" class="text-destructive hover:text-destructive" @click="emit('delete', movie)">
        <Trash2 class="h-3 w-3" />
      </Button>
    </CardFooter>
  </Card>
</template>
