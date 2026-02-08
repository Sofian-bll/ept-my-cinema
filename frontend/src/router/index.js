import { createRouter, createWebHistory } from 'vue-router'

// Import pages
import DashboardPage from '@/pages/DashboardPage.vue'
import MoviesPage from '@/pages/MoviesPage.vue'
import RoomsPage from '@/pages/RoomsPage.vue'
import ScreeningsPage from '@/pages/ScreeningsPage.vue'
import SettingsPage from '@/pages/SettingsPage.vue'

/**
 * Route
 */
const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardPage,
    meta: {
      title: 'Dashboard'
    }
  },
  {
    path: '/movies',
    name: 'Movies',
    component: MoviesPage,
    meta: {
      title: 'Movies'
    }
  },
  {
    path: '/rooms',
    name: 'Rooms',
    component: RoomsPage,
    meta: {
      title: 'Rooms'
    }
  },
  {
    path: '/screenings',
    name: 'Screenings',
    component: ScreeningsPage,
    meta: {
      title: 'Screenings'
    }
  },
  {
    path: '/settings',
    name: 'Settings',
    component: SettingsPage,
    meta: {
      title: 'Settings'
    }
  },
  {
    // Catch-all 404
    path: '/:pathMatch(.*)*',
    redirect: '/dashboard'
  }
]

/**
 * Create Vue Router
 */
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    return { top: 0 }
  }
})

/**
 * Update document title on navigation
 */
router.beforeEach((to, from, next) => {
  const title = to.meta?.title || 'My Cinema'
  document.title = `${title} | My Cinema`
  next()
})

export default router
