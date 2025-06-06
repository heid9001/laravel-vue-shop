<template>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Мои заказы</h5>

                        <div v-if="loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Загрузка...</span>
                            </div>
                        </div>

                        <div v-else-if="error" class="alert alert-danger">
                            {{ error }}
                        </div>

                        <div v-else-if="orders.data.length === 0" class="text-center py-4">
                            <i class="bi bi-box fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">У вас пока нет заказов</p>
                        </div>

                        <div v-else>
                            <div v-for="order in orders.data" :key="order.id" class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1">Заказ #{{ order.id }}</h6>
                                            <p class="text-muted mb-0">
                                                {{ new Date(order.created_at).toLocaleDateString() }}
                                            </p>
                                            <p>
                                                ФИО: {{ order.user.full_name }}
                                            </p>
                                        </div>
                                        <span class="badge" :class="{
                                            'bg-success': order.status === 'complete',
                                            'bg-warning': order.status === 'new'
                                        }">
                                            {{ getStatusText(order.status) }}
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Товар</th>
                                                    <th class="text-center">Количество</th>
                                                    <th class="text-end">Цена</th>
                                                    <th class="text-end">Итого</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in order.orderItems" :key="item.id">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <h6 class="mb-0">{{ item.product || 'Товар недоступен' }}</h6>
                                                                <small class="text-muted">
                                                                    {{ item.category || 'Категория недоступна' }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ item.qty }}</td>
                                                    <td class="text-end">{{ item.price }} ₽</td>
                                                    <td class="text-end">{{ (item.price * item.qty).toFixed(2) }} ₽</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                                    <td class="text-end"><strong>{{ order.total_amount }} ₽</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Pagination
                            :meta="orders.meta"
                            @page-changed="handlePageChange"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { orderService } from '../../services/order'
import Pagination from '@/components/Pagination.vue'

const orders = ref({ data: [], meta: {} })
const loading = ref(true)
const error = ref(null)

const fetchOrders = async (page = 1) => {
    loading.value = true
    error.value = null
    try {
        orders.value = await orderService.fetchOrders(page)
        console.log(orders.value);
    } catch (err) {
        error.value = 'Не удалось загрузить заказы'
        console.error('Error fetching orders:', err)
    } finally {
        loading.value = false
    }
}

const handlePageChange = (page) => {
    fetchOrders(page)
}

const getStatusText = (status) => {
    const statusMap = {
        'new': 'Новый',
        'completed': 'Выполнен'
    }
    return statusMap[status] || status
}

onMounted(() => {
    fetchOrders()
})
</script>

<style scoped>
.table th {
    font-weight: 600;
    color: #6c757d;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5em 1em;
}
</style>
