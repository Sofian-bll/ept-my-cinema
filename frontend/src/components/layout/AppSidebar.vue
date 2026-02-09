<script setup>
import { RouterLink, useRoute } from 'vue-router'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarRail,
} from '@/components/ui/sidebar'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import {
  LayoutDashboard,
  Film,
  DoorOpen,
  CalendarClock,
  Settings,
  Clapperboard
} from 'lucide-vue-next'

const route = useRoute()

const mainNavItems = [
  {
    title: 'Dashboard',
    url: '/dashboard',
    icon: LayoutDashboard
  },
  {
    title: 'Movies',
    url: '/movies',
    icon: Film
  },
  {
    title: 'Rooms',
    url: '/rooms',
    icon: DoorOpen
  },
  {
    title: 'Screenings',
    url: '/screenings',
    icon: CalendarClock
  }
]

const settingsNavItems = [
  {
    title: 'Settings',
    url: '/settings',
    icon: Settings
  }
]

function isActive(url) {
  return route.path === url || route.path.startsWith(url + '/')
}
</script>

<template>
  <Sidebar collapsible="icon" variant="sidebar">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <RouterLink to="/dashboard" class="flex items-center gap-2">
              <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                <Clapperboard class="size-4" />
              </div>
              <div class="grid flex-1 text-left text-sm leading-tight">
                <span class="truncate font-semibold">My Cinema</span>
                <span class="truncate text-xs text-muted-foreground">Management</span>
              </div>
            </RouterLink>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <SidebarGroup>
        <SidebarGroupLabel>Navigation</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
              <SidebarMenuButton 
                as-child 
                :is-active="isActive(item.url)"
                tooltip-title=" item.title"
              >
                <RouterLink :to="item.url">
                  <component :is="item.icon" />
                  <span>{{ item.title }}</span>
                </RouterLink>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>

      <SidebarGroup class="mt-auto">
        <SidebarGroupLabel>System</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in settingsNavItems" :key="item.title">
              <SidebarMenuButton 
                as-child 
                :is-active="isActive(item.url)"
                :tooltip-title="item.title"
              >
                <RouterLink :to="item.url">
                  <component :is="item.icon" />
                  <span>{{ item.title }}</span>
                </RouterLink>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>

    <SidebarFooter>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg">
            <Avatar class="h-8 w-8 rounded-lg">
              <AvatarFallback class="rounded-lg bg-accent text-accent-foreground">
                CM
              </AvatarFallback>
            </Avatar>
            <div class="grid flex-1 text-left text-sm leading-tight">
              <span class="truncate font-semibold">Cinema Manager</span>
              <span class="truncate text-xs text-muted-foreground">admin@mycinema.com</span>
            </div>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarFooter>

    <SidebarRail />
  </Sidebar>
</template>
