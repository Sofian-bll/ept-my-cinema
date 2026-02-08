<script setup>
import { ref, onMounted, provide } from 'vue'
import { Film, DoorOpen, Calendar, LayoutDashboard } from 'lucide-vue-next'
import { Toaster } from '@/components/ui/sonner'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import ThemeToggle from '@/components/ThemeToggle.vue'
import MoviesTab from '@/components/movies/MoviesTab.vue'
import RoomsTab from '@/components/rooms/RoomsTab.vue'
import ScreeningsTab from '@/components/screenings/ScreeningsTab.vue'
import { moviesApi, roomsApi, screeningsApi } from '@/services/api.js'

const currentTab = ref('movies')
const stats = ref({ movies: 0, rooms: 0, screenings: 0 })

const navItems = [
  { key: 'movies', label: 'Films', icon: Film },
  { key: 'rooms', label: 'Salles', icon: DoorOpen },
  { key: 'screenings', label: 'Seances', icon: Calendar },
]

async function fetchStats() {
  try {
    const [movies, rooms, screenings] = await Promise.all([
      moviesApi.list(),
      roomsApi.list(),
      screeningsApi.list(),
    ])
    stats.value = {
      movies: movies.length,
      rooms: rooms.length,
      screenings: screenings.length,
    }
  } catch {
    // stats silently fail
  }
}

provide('refreshStats', fetchStats)

onMounted(fetchStats)
</script>

<template>
  <div class="flex h-screen">
    <aside class="flex w-56 flex-col border-r bg-card">
      <div class="flex items-center gap-2 px-4 py-5">
        <LayoutDashboard class="size-5 text-primary" />
        <span class="text-lg font-semibold">My Cinema</span>
      </div>

      <Separator />

      <nav class="flex-1 space-y-1 px-2 py-3">
        <button
          v-for="item in navItems"
          :key="item.key"
          class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors"
          :class="currentTab === item.key
            ? 'bg-primary text-primary-foreground'
            : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground'"
          @click="currentTab = item.key"
        >
          <component :is="item.icon" class="size-4" />
          {{ item.label }}
        </button>
      </nav>

      <Separator />

      <div class="flex items-center justify-between px-4 py-3">
        <span class="text-xs text-muted-foreground">Theme</span>
        <ThemeToggle />
      </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-background p-6">
      <div class="mb-6 grid grid-cols-3 gap-4">
        <Card>
          <CardHeader class="flex flex-row items-center justify-between pb-2">
            <CardTitle class="text-sm font-medium">Films</CardTitle>
            <Film class="size-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.movies }}</div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between pb-2">
            <CardTitle class="text-sm font-medium">Salles actives</CardTitle>
            <DoorOpen class="size-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.rooms }}</div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between pb-2">
            <CardTitle class="text-sm font-medium">Seances</CardTitle>
            <Calendar class="size-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.screenings }}</div>
          </CardContent>
        </Card>
      </div>

      <MoviesTab v-if="currentTab === 'movies'" />
      <RoomsTab v-else-if="currentTab === 'rooms'" />
      <ScreeningsTab v-else-if="currentTab === 'screenings'" />
    </main>
  </div>

  <Toaster rich-colors position="top-right" />
</template>
