import axios from 'axios'
import {CART_STORAGE_KEY} from "./cart.js";

const API_URL = '/api/v1'

export const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

api.interceptors.request.use(
    config => {
        const token = localStorage.getItem('access_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    error => {
        return Promise.reject(error)
    }
)

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response.status === 401) {
            localStorage.removeItem('access_token')
        }
        return Promise.reject(error)
    }
)

export const productService = {
    fetchProducts: async (page = 1) => {
        const response = await api.get(`/products?page=${page}`)
        return response.data
    },

    fetchProduct: async (id) => {
        const response = await api.get(`/products/${id}`)
        return response.data
    },

    createProduct: async (data) => {
        const response = await api.post('/products', data)
        return response.data
    },

    updateProduct: async (id, data) => {
        const response = await api.put(`/products/${id}`, data)
        return response.data
    },

    deleteProduct: async (id) => {
        try {
            const response = await api.delete(`/products/${id}`)
            return response.data
        } catch (error) {
            if (error.response?.status === 422) {
                throw new Error('Невозможно удалить товар, так как он связан с существующими заказами')
            }
            throw new Error('Не удалось удалить товар')
        }
    },

    fetchCategories: async () => {
        const response = await api.get('/categories')
        return response.data
    }
}

export const authService = {
    async login(credentials) {
        const response = await api.post('/auth/login', credentials)
        localStorage.setItem('access_token', response.data.access_token)
        return response.data
    },

    async register(userData) {
        userData.name = userData.name ?? userData.email;
        const response = await api.post('/auth/register', userData)
        localStorage.setItem('access_token', response.data.access_token)
        return response.data
    },

    async logout() {
        await api.post('/auth/logout')
        localStorage.removeItem('access_token')
    },

    async getUserInfo() {
        const response = await api.get('/auth/me')
        return response.data
    }
}

export const orderService = {
    createOrder: async (cartItems) => {
        const orderItems = cartItems.map(item => ({
            product_id: item.id,
            qty: item.quantity
        }));

        const response = await api.post('/orders', {
            orderItems
        });
        localStorage.removeItem(CART_STORAGE_KEY);
        return response.data;
    },

    fetchOrders: async (page = 1) => {
        const response = await api.get(`/orders/?page=${page}`);
        return response.data;
    },

    fetchOrder: async (id) => {
        const response = await api.get(`/orders/${id}`);
        return response.data;
    }
}
