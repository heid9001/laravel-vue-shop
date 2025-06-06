import { api } from './api'

export const orderService = {
    async fetchOrders(page = 1) {
        const response = await api.get(`/orders?page=${page}`)
        return response.data
    },

    async createOrder(orderData) {
        const response = await api.post('/orders', orderData)
        return response.data
    },

    async getOrder(id) {
        const response = await api.get(`/orders/${id}`)
        return response.data
    },

    async updateOrderStatus(id, status) {
        const response = await api.patch(`/orders/${id}/status`, { status })
        return response.data
    }
} 