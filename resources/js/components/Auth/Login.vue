<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>

                        <form @submit.prevent="handleSubmit">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    v-model="form.email"
                                    :class="{ 'is-invalid': errors.email }"
                                    required
                                >
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    v-model="form.password"
                                    :class="{ 'is-invalid': errors.password }"
                                    required
                                >
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password }}
                                </div>
                            </div>

                            <div class="alert alert-danger" v-if="error">
                                {{ error }}
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                :disabled="loading"
                            >
                                <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                                {{ loading ? 'Logging in...' : 'Login' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '../../services/api'

export default {
    name: 'Login',
    setup() {
        const router = useRouter()
        const form = reactive({
            email: '',
            password: ''
        })
        const loading = ref(false)
        const error = ref('')
        const errors = ref({
            email: '',
            password: ''
        })

        const handleSubmit = async () => {
            error.value = ''
            errors.value = {
                email: '',
                password: ''
            }

            if (!form.email && !validator.isEmail(form.email.value)) {
                errors.value.email = 'Ошибка авторизации'
                return
            }
            if (!form.password && !form.password.length > 8) {
                errors.value.password = 'Ошибка авторизации'
                return
            }

            loading.value = true
            try {
                await authService.login(form)
                router.push('/').then(()=>window.location.reload());
            } catch (err) {
                if (err.response?.data?.errors) {
                    errors.value = 'Ошибка авторизации'
                } else {
                    error.value = 'Ошибка авторизации'
                }
            } finally {
                loading.value = false
            }
        }

        return {
            form,
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

.form-control:focus {
    box-shadow: none;
    border-color: #0d6efd;
}
</style>
