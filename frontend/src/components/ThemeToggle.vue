<script setup>
import { useColorMode } from '@vueuse/core'
import { Sun, Moon } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

const mode = useColorMode()

function toggle() {
  mode.value = mode.value === 'dark' ? 'light' : 'dark'
}
</script>

<template>
  <Button variant="ghost" size="icon" @click="toggle">
    <Sun v-if="mode === 'dark'" class="size-5" />
    <Moon v-else class="size-5" />
  </Button>
</template>
