import '../css/app.css'
import './bootstrap'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import AppShell from './components/layout/AppShell.vue'

const app = createApp(AppShell)

app.use(createPinia())
app.use(router)

app.mount('#app')
