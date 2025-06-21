import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from './Pages/Dashboard.vue'
import Content from './Pages/Content.vue'

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/content/:id',
        name: 'content',
        component: Content
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router 