import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from './Pages/Dashboard.vue'
import Product from './Pages/Product.vue'

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/product/:id',
        name: 'product',
        component: Product
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router 