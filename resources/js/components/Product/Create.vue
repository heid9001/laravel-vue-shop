<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Create Product</h2>

                        <form @submit.prevent="handleSubmit">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    v-model="form.name"
                                    :class="{ 'is-invalid': errors.name }"
                                    required
                                >
                                <div class="invalid-feedback" v-if="errors.name">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select
                                    class="form-select"
                                    id="category"
                                    v-model="form.category_id"
                                    :class="{ 'is-invalid': errors.category_id }"
                                    required
                                >
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback" v-if="errors.category_id">
                                    {{ errors.category_id }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea
                                    class="form-control"
                                    id="description"
                                    v-model="form.description"
                                    :class="{ 'is-invalid': errors.description }"
                                    rows="3"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.description">
                                    {{ errors.description }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="price"
                                        v-model="form.price"
                                        :class="{ 'is-invalid': errors.price }"
                                        step="0.01"
                                        min="0"
                                        required
                                    >
                                    <span class="input-group-text"> ₽</span>
                                    <div class="invalid-feedback" v-if="errors.price">
                                        {{ errors.price }}
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-danger" v-if="error">
                                {{ error }}
                            </div>

                            <div class="d-flex justify-content-between">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="$router.push('/products')"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading"
                                >
                                    <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    {{ loading ? 'Creating...' : 'Create Product' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { productService } from '../../services/api'

export default {
    name: 'ProductCreate',
    setup() {
        const router = useRouter()
        const form = reactive({
            name: '',
            category_id: '',
            description: '',
            price: ''
        })
        const categories = ref([])
        const loading = ref(false)
        const error = ref('')
        const errors = ref({})

        const fetchCategories = async () => {
            try {
                const response = await productService.fetchCategories()
                categories.value = response.data
            } catch (err) {
                error.value = 'Failed to load categories'
            }
        }

        const handleSubmit = async () => {
            error.value = ''
            errors.value = {}
            loading.value = true

            try {
                await productService.createProduct(form)
                router.push('/products')
            } catch (err) {
                if (err.response?.data?.errors) {
                    errors.value = err.response.data.errors
                } else {
                    error.value = err.response?.data?.message || 'Failed to create product'
                }
            } finally {
                loading.value = false
            }
        }

        onMounted(() => {
            fetchCategories()
        })

        return {
            form,
            categories,
            loading,
            error,
            errors,
            handleSubmit
        }
    }
}
</script>

<style scoped>
.card {
    border: none;
    border-radius: 10px;
}

.form-control:focus,
.form-select:focus {
    box-shadow: none;
    border-color: #0d6efd;
}
</style>
