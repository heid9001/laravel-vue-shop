<template>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Корзина</h5>
                        <div v-if="cart.length === 0" class="text-center py-4">
                            <i class="bi bi-cart3 fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">Your cart is empty</p>
                        </div>
                        <div v-else>
                            <div v-for="item in cart" :key="item.id" class="cart-item mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ item.name }}</h6>
                                        <p class="text-muted mb-0">{{ item.price }} ₽</p>
                                    </div>
                                    <div class="quantity-group">
                                        <button
                                            @click="updateQuantity(item.id, item.quantity - 1)"
                                            class="btn btn-outline-secondary btn-sm"
                                            :disabled="item.quantity <= 1"
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input
                                            type="number"
                                            v-model.number="item.quantity"
                                            @change="updateQuantity(item.id, item.quantity)"
                                            class="form-control form-control-sm text-center"
                                            min="1"
                                        >
                                        <button
                                            @click="updateQuantity(item.id, item.quantity + 1)"
                                            class="btn btn-outline-secondary btn-sm"
                                        >
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <button
                                        @click="removeFromCart(item.id)"
                                        class="btn btn-link text-danger ms-3"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Заказ</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Кол-во ({{ getItemCount() }})</span>
                            <span>{{ getTotal() }} ₽</span>
                        </div>
                        <button
                            @click="checkout"
                            class="btn btn-primary w-100"
                            :disabled="cart.length === 0"
                        >
                            Оформить заказ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { cartService } from '../../services/cart'
import { orderService } from '../../services/order'

const router = useRouter()
const cart = ref([])
const loading = ref(false)

const loadCart = () => {
    cart.value = cartService.getCart()
}

const updateQuantity = (productId, quantity) => {
    cartService.updateQuantity(productId, quantity)
    loadCart()
}

const removeFromCart = (productId) => {
    cartService.removeFromCart(productId)
    loadCart()
}

const getTotal = () => {
    return cartService.getTotal().toFixed(2)
}

const getItemCount = () => {
    return cartService.getItemCount()
}

const checkout = async () => {
    if (!localStorage.getItem('access_token')) {
        router.push({ name: 'login' })
        return
    }

    loading.value = true
    try {
        const orderData = {
            orderItems: cart.value.map(item => ({
                product_id: item.id,
                qty: item.quantity,
                price: item.price
            })),
            total_amount: getTotal()
        }

        await orderService.createOrder(orderData)
        cartService.clearCart()
        loadCart()
        router.push({ name: 'orders' })
    } catch (error) {
        console.error('Checkout failed:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadCart()
})
</script>

<style scoped>
.quantity-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    width: 150px;
}

.quantity-group input {
    width: 60px;
    text-align: center;
    padding: 0.25rem;
}

.cart-item {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.cart-item:last-child {
    border-bottom: none;
}
</style>
