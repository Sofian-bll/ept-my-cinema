<script setup>
import { getRoomTypeVariant } from '@/lib/utils'
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Pencil, Trash2, Users, DoorOpen } from 'lucide-vue-next'

const props = defineProps({
  room: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])
</script>

<template>
  <Card class="overflow-hidden transition-all hover:shadow-lg hover:border-primary/50">
    <CardHeader class="pb-3">
      <div class="flex items-start justify-between gap-2">
        <div class="flex items-center gap-2">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
            <DoorOpen class="h-5 w-5 text-primary" />
          </div>
          <CardTitle class="text-lg">{{ room.name }}</CardTitle>
        </div>
        <Badge :variant="getRoomTypeVariant(room.type)">
          {{ room.type || 'Standard' }}
        </Badge>
      </div>
    </CardHeader>

    <CardContent>
      <div class="flex items-center gap-2 text-sm text-muted-foreground">
        <Users class="h-4 w-4" />
        <span>{{ room.capacity }} seats</span>
      </div>
    </CardContent>

    <CardFooter class="flex gap-2 pt-0">
      <Button variant="outline" size="sm" class="flex-1" @click="emit('edit', room)">
        <Pencil class="mr-2 h-3 w-3" />
        Edit
      </Button>
      <Button variant="outline" size="sm" class="text-destructive hover:text-destructive" @click="emit('delete', room)">
        <Trash2 class="h-3 w-3" />
      </Button>
    </CardFooter>
  </Card>
</template>
