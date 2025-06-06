import { createRouter, createWebHistory } from 'vue-router'
import ProductList from '../components/Product/List.vue'
import ProductCreate from '../components/Product/Create.vue'
import ProductUpdate from '../components/Product/Update.vue'
import Cart from '../components/Cart/Cart.vue'
import OrderList from '../components/Order/List.vue'
import Login from '../components/Auth/Login.vue'
import Register from '../components/Auth/Register.vue'

const routes = [
    {
        path: '/',
        redirect: '/products'
    },
    {
        path: '/products',
        name: 'products',
        component: ProductList
    },
    {
        path: '/products/create',
        name: 'products.create',
        component: ProductCreate
    },
    {
        path: '/products/:id/edit',
        name: 'products.edit',
        component: ProductUpdate
    },
    {
        path: '/cart',
        name: 'cart',
        component: Cart
    },
    {
        path: '/orders',
        name: 'orders',
        component: OrderList
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('access_token')

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'login' })
    } else if (to.meta.guest && isAuthenticated) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router
