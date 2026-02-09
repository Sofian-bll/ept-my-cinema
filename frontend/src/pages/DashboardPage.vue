<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useMovies, useRooms, useScreenings } from '@/composables'
import { formatDuration, formatDateTime } from '@/lib/utils'
import { AppLayout } from '@/components/layout'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Skeleton } from '@/components/ui/skeleton'
import { ScrollArea } from '@/components/ui/scroll-area'
import {
  Film,
  DoorOpen,
  CalendarClock,
  TrendingUp,
  Plus,
  ArrowRight,
  Clock,
  Users
} from 'lucide-vue-next'

const router = useRouter()

const { movies, fetchAll: fetchMovies, loading: moviesLoading } = useMovies()
const { rooms, fetchAll: fetchRooms, loading: roomsLoading } = useRooms()
const { screenings, todayScreenings, upcomingScreenings, fetchAll: fetchScreenings, loading: screeningsLoading } = useScreenings()

const loading = computed(() => moviesLoading.value || roomsLoading.value || screeningsLoading.value)

const stats = computed(() => [
  {
    title: 'Total Movies',
    value: movies.value.length,
    icon: Film,
    description: 'In catalog',
    color: 'text-primary'
  },
  {
    title: 'Active Rooms',
    value: rooms.value.length,
    icon: DoorOpen,
    description: 'Available',
    color: 'text-green-500'
  },
  {
    title: "Today's Screenings",
    value: todayScreenings.value.length,
    icon: CalendarClock,
    description: 'Scheduled today',
    color: 'text-amber-500'
  },
  {
    title: 'Upcoming',
    value: upcomingScreenings.value.length,
    icon: TrendingUp,
    description: 'Next 7 days',
    color: 'text-blue-500'
  }
])

function getMovie(movieId) {
  return movies.value.find(m => m.id === movieId)
}

function getRoom(roomId) {
  return rooms.value.find(r => r.id === roomId)
}

const recentMovies = computed(() => {
  return [...movies.value]
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 5)
})

onMounted(async () => {
  await Promise.all([
    fetchMovies(),
    fetchRooms(),
    fetchScreenings()
  ])
})
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
          <p class="text-muted-foreground">
            Welcome to My Cinema management dashboard
          </p>
        </div>
        <div class="flex gap-2">
          <Button @click="router.push('/movies')">
            <Plus class="mr-2 h-4 w-4" />
            Add Movie
          </Button>
          <Button variant="outline" @click="router.push('/screenings')">
            <CalendarClock class="mr-2 h-4 w-4" />
            Schedule
          </Button>
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card v-for="stat in stats" :key="stat.title">
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">{{ stat.title }}</CardTitle>
            <component :is="stat.icon" :class="['h-4 w-4', stat.color]" />
          </CardHeader>
          <CardContent>
            <template v-if="loading">
              <Skeleton class="h-8 w-16" />
            </template>
            <template v-else>
              <div class="text-2xl font-bold">{{ stat.value }}</div>
              <p class="text-xs text-muted-foreground">{{ stat.description }}</p>
            </template>
          </CardContent>
        </Card>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
        <Card class="lg:col-span-4">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div>
                <CardTitle>Upcoming Screenings</CardTitle>
                <CardDescription>Next 7 days schedule</CardDescription>
              </div>
              <Button variant="ghost" size="sm" @click="router.push('/screenings')">
                View All
                <ArrowRight class="ml-2 h-4 w-4" />
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <ScrollArea class="h-[300px]">
              <div v-if="loading" class="space-y-4">
                <Skeleton v-for="i in 4" :key="i" class="h-16 w-full" />
              </div>
              <div v-else-if="upcomingScreenings.length" class="space-y-4">
                <div
                  v-for="screening in upcomingScreenings.slice(0, 6)"
                  :key="screening.id"
                  class="flex items-center gap-4 rounded-lg border p-3"
                >
                  <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <Film class="h-6 w-6 text-primary" />
                  </div>
                  <div class="flex-1 space-y-1">
                    <p class="font-medium leading-none">
                      {{ getMovie(screening.movies_id)?.title || 'Unknown Movie' }}
                    </p>
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                      <DoorOpen class="h-3 w-3" />
                      <span>{{ getRoom(screening.room_id)?.name || 'Unknown Room' }}</span>
                      <span class="text-muted-foreground">•</span>
                      <Clock class="h-3 w-3" />
                      <span>{{ formatDateTime(screening.start_time) }}</span>
                    </div>
                  </div>
                  <Badge variant="outline" class="text-green-500 border-green-500">
                    Upcoming
                  </Badge>
                </div>
              </div>
              <div v-else class="flex flex-col items-center justify-center h-[200px] text-muted-foreground">
                <CalendarClock class="h-12 w-12 mb-4 opacity-50" />
                <p>No upcoming screenings</p>
                <Button variant="link" size="sm" @click="router.push('/screenings')">
                  Schedule one now
                </Button>
              </div>
            </ScrollArea>
          </CardContent>
        </Card>

        <Card class="lg:col-span-3">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div>
                <CardTitle>Recent Movies</CardTitle>
                <CardDescription>Latest additions to catalog</CardDescription>
              </div>
              <Button variant="ghost" size="sm" @click="router.push('/movies')">
                View All
                <ArrowRight class="ml-2 h-4 w-4" />
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <ScrollArea class="h-[300px]">
              <div v-if="loading" class="space-y-4">
                <Skeleton v-for="i in 4" :key="i" class="h-14 w-full" />
              </div>
              <div v-else-if="recentMovies.length" class="space-y-4">
                <div
                  v-for="movie in recentMovies"
                  :key="movie.id"
                  class="flex items-center gap-3"
                >
                  <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent/10">
                    <Film class="h-5 w-5 text-accent" />
                  </div>
                  <div class="flex-1 space-y-1">
                    <p class="text-sm font-medium leading-none">{{ movie.title }}</p>
                    <div class="flex items-center gap-2 text-xs text-muted-foreground">
                      <span>{{ movie.release_year }}</span>
                      <span>•</span>
                      <span>{{ formatDuration(movie.duration) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="flex flex-col items-center justify-center h-[200px] text-muted-foreground">
                <Film class="h-12 w-12 mb-4 opacity-50" />
                <p>No movies in catalog</p>
                <Button variant="link" size="sm" @click="router.push('/movies')">
                  Add your first movie
                </Button>
              </div>
            </ScrollArea>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <div class="flex items-center justify-between">
            <div>
              <CardTitle>Rooms Overview</CardTitle>
              <CardDescription>All active cinema rooms</CardDescription>
            </div>
            <Button variant="ghost" size="sm" @click="router.push('/rooms')">
              Manage Rooms
              <ArrowRight class="ml-2 h-4 w-4" />
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          <div v-if="loading" class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
            <Skeleton v-for="i in 5" :key="i" class="h-24" />
          </div>
          <div v-else-if="rooms.length" class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
            <div
              v-for="room in rooms"
              :key="room.id"
              class="rounded-lg border p-4 transition-colors hover:bg-muted/50"
            >
              <div class="flex items-center gap-2 mb-2">
                <DoorOpen class="h-4 w-4 text-primary" />
                <span class="font-medium">{{ room.name }}</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <Users class="h-3 w-3" />
                <span>{{ room.capacity }} seats</span>
              </div>
              <Badge v-if="room.type" variant="secondary" class="mt-2 text-xs">
                {{ room.type }}
              </Badge>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center py-8 text-muted-foreground">
            <DoorOpen class="h-12 w-12 mb-4 opacity-50" />
            <p>No rooms configured</p>
            <Button variant="link" size="sm" @click="router.push('/rooms')">
              Add your first room
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
