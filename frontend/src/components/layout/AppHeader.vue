<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { SidebarTrigger } from '@/components/ui/sidebar'
import { Separator } from '@/components/ui/separator'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator
} from '@/components/ui/breadcrumb'
import ThemeToggle from './ThemeToggle.vue'

const route = useRoute()

const breadcrumbs = computed(() => {
  const segments = route.path.split('/').filter(Boolean)
  const items = []
  
  let currentPath = ''
  segments.forEach((segment, index) => {
    currentPath += `/${segment}`
    items.push({
      title: segment.charAt(0).toUpperCase() + segment.slice(1),
      href: currentPath,
      isLast: index === segments.length - 1
    })
  })
  
  return items
})
</script>

<template>
  <header class="flex h-16 shrink-0 items-center gap-2 border-b border-border px-4">
    <SidebarTrigger class="-ml-1" />
    <Separator orientation="vertical" class="mr-2 h-4" />
    
    <Breadcrumb>
      <BreadcrumbList>
        <BreadcrumbItem class="hidden md:block">
          <BreadcrumbLink href="/dashboard">
            Home
          </BreadcrumbLink>
        </BreadcrumbItem>
        
        <template v-for="(item, index) in breadcrumbs" :key="item.href">
          <BreadcrumbSeparator class="hidden md:block" />
          <BreadcrumbItem>
            <BreadcrumbPage v-if="item.isLast">
              {{ item.title }}
            </BreadcrumbPage>
            <BreadcrumbLink v-else :href="item.href">
              {{ item.title }}
            </BreadcrumbLink>
          </BreadcrumbItem>
        </template>
      </BreadcrumbList>
    </Breadcrumb>

    <div class="ml-auto flex items-center gap-2">
      <slot name="actions" />
      <ThemeToggle />
    </div>
  </header>
</template>
