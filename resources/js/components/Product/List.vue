<template>
    <div class="container py-4">
        <div class="d-flex justify-content-end mb-4">
            <router-link
                :to="{ name: 'products.create' }"
                class="btn btn-primary rounded-circle"
                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
            >
                <i class="bi bi-plus-lg fs-5"></i>
            </router-link>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="product in products.data" :key="product.id" class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text text-muted">{{ product.description }}</p>
                        <div class="d-flex flex-column gap-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h4 mb-0">{{ product.price }} ₽</span>
                                <div class="btn-group">
                                    <router-link
                                        :to="{ name: 'products.edit', params: { id: product.id }}"
                                        class="btn btn-outline-primary rounded-circle"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                    >
                                        <i class="bi bi-pencil fs-6"></i>
                                    </router-link>
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="btn btn-outline-danger rounded-circle"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                    >
                                        <i class="bi bi-trash fs-6"></i>
                                    </button>
                                    <button
                                        @click="addToCart(product)"
                                        class="btn btn-primary"
                                        :disabled="isInCart(product.id)"
                                    >
                                        {{ isInCart(product.id) ? 'В корзине' : 'В корзину' }}
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <span class="badge bg-secondary">{{ product.category.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Pagination
            :meta="products.meta"
            @page-changed="handlePageChange"
        />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import Pagination from '../Pagination.vue'
import { productService } from '../../services/api'
import { cartService } from '../../services/cart'

export default {
    name: 'ProductList',
    components: {
        Pagination
    },
    setup() {
        const products = ref({
            data: [],
            links: [],
            meta: {
                current_page: 1,
                from: 1,
                to: 1,
                total: 0,
                links: []
            }
        })

        const fetchProducts = async (url) => {
            try {
                products.value = await productService.fetchProducts(url)
            } catch (error) {
                console.error('Failed to fetch products:', error)
            }
        }

        const handlePageChange = async (url) => {
            if (url) {
                await fetchProducts(url)
            }
        }

        const addToCart = (product) => {
            cartService.addToCart(product)
        }

        const isInCart = (productId) => {
            const cart = cartService.getCart()
            return cart.some(item => item.id === productId)
        }

        const deleteProduct = async (productId) => {
            if (!confirm('Are you sure you want to delete this product?')) {
                return
            }

            try {
                await productService.deleteProduct(productId)
                await fetchProducts()
            } catch (error) {
                console.error('Failed to delete product:', error)
                alert('Failed to delete product. Please try again.')
            }
        }

        onMounted(() => {
            fetchProducts()
        })

        return {
            products,
            handlePageChange,
            addToCart,
            isInCart,
            deleteProduct
        }
    }
}
</script>

<style scoped>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.page-link {
    cursor: pointer;
}

.btn-group {
    gap: 0.5rem;
}
</style>
