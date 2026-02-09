<script setup>
import { AppLayout } from '@/components/layout'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'
import { Separator } from '@/components/ui/separator'
import { Badge } from '@/components/ui/badge'
import { Settings, Database, Palette, Info } from 'lucide-vue-next'
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight flex items-center gap-2">
          <Settings class="h-8 w-8 text-primary" />
          Settings
        </h1>
        <p class="text-muted-foreground">
          Configure your cinema dashboard preferences
        </p>
      </div>

      <div class="grid gap-6">
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Database class="h-5 w-5" />
              API Configuration
            </CardTitle>
            <CardDescription>
              Configure the backend API connection
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="space-y-2">
              <Label htmlFor="api-url">API Base URL</Label>
              <Input
                id="api-url"
                value="http://localhost:8000/api"
                disabled
              />
              <p class="text-xs text-muted-foreground">
                Currently using hardcoded development URL
              </p>
            </div>
            <div class="flex items-center gap-2">
              <Badge variant="outline" class="text-green-500 border-green-500">
                Connected
              </Badge>
              <span class="text-sm text-muted-foreground">API is reachable</span>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Palette class="h-5 w-5" />
              Appearance
            </CardTitle>
            <CardDescription>
              Customize the look and feel
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="space-y-0.5">
                <Label>Theme</Label>
                <p class="text-xs text-muted-foreground">
                  Cinema dark theme with red/gold accents
                </p>
              </div>
              <Badge>Dark</Badge>
            </div>
            <Separator />
            <div class="flex items-center justify-between">
              <div class="space-y-0.5">
                <Label>Primary Color</Label>
                <p class="text-xs text-muted-foreground">
                  Cinema red accent color
                </p>
              </div>
              <div class="flex items-center gap-2">
                <div class="h-6 w-6 rounded-full bg-primary" />
                <span class="text-sm text-muted-foreground">#dc2626</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="space-y-0.5">
                <Label>Accent Color</Label>
                <p class="text-xs text-muted-foreground">
                  Gold accent color
                </p>
              </div>
              <div class="flex items-center gap-2">
                <div class="h-6 w-6 rounded-full bg-accent" />
                <span class="text-sm text-muted-foreground">#d97706</span>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Info class="h-5 w-5" />
              About
            </CardTitle>
            <CardDescription>
              Application information
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-1">
                <Label class="text-muted-foreground">Application</Label>
                <p class="font-medium">My Cinema Dashboard</p>
              </div>
              <div class="space-y-1">
                <Label class="text-muted-foreground">Version</Label>
                <p class="font-medium">1.0.0</p>
              </div>
              <div class="space-y-1">
                <Label class="text-muted-foreground">Framework</Label>
                <p class="font-medium">Vue 3 + Vite</p>
              </div>
              <div class="space-y-1">
                <Label class="text-muted-foreground">UI Library</Label>
                <p class="font-medium">shadcn-vue</p>
              </div>
            </div>
            <Separator />
            <div class="space-y-2">
              <Label class="text-muted-foreground">Tech Stack</Label>
              <div class="flex flex-wrap gap-2">
                <Badge variant="secondary">Vue 3</Badge>
                <Badge variant="secondary">Vite</Badge>
                <Badge variant="secondary">TailwindCSS</Badge>
                <Badge variant="secondary">shadcn-vue</Badge>
                <Badge variant="secondary">TanStack Table</Badge>
                <Badge variant="secondary">Vue Router</Badge>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
