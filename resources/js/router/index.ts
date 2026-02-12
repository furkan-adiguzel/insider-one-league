import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import DashboardView from '../views/DashboardView.vue'
import TeamsView from '../views/TeamsView.vue'

const routes: RouteRecordRaw[] = [
    { path: '/ui', redirect: '/ui/dashboard' },
    { path: '/ui/dashboard', component: DashboardView },
    { path: '/ui/teams', component: TeamsView },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})
