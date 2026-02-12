import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import DashboardView from '../views/DashboardView.vue'
import TeamsView from '../views/TeamsView.vue'

const routes: RouteRecordRaw[] = [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', component: DashboardView },
    { path: '/teams', component: TeamsView },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})
