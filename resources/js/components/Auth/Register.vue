<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Регистрация</h2>

                        <form @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="surname" class="form-label">Фамилия</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="surname"
                                        v-model="form.surname"
                                        :class="{ 'is-invalid': errors.surname }"
                                        required
                                    >
                                    <div class="invalid-feedback" v-if="errors.surname">
                                        {{ errors.surname }}
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">Имя</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        v-model="form.firstname"
                                        :class="{ 'is-invalid': errors.firstname }"
                                        required
                                    >
                                    <div class="invalid-feedback" v-if="errors.firstname">
                                        {{ errors.firstname }}
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="patronymic" class="form-label">Отчество</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="patronymic"
                                        v-model="form.patronymic"
                                        :class="{ 'is-invalid': errors.patronymic }"
                                        required
                                    >
                                    <div class="invalid-feedback" v-if="errors.patronymic">
                                        {{ errors.patronymic }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
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
                                <label for="password" class="form-label">Пароль</label>
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

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Подтвердите пароль</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :class="{ 'is-invalid': errors.password_confirmation }"
                                    required
                                >
                                <div class="invalid-feedback" v-if="errors.password_confirmation">
                                    {{ errors.password_confirmation }}
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
                                {{ loading ? 'Регистрация...' : 'Регистрация' }}
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
    name: 'Register',
    setup() {
        const router = useRouter()
        const form = reactive({
            surname: '',
            firstname: '',
            patronymic: '',
            email: '',
            password: '',
            password_confirmation: ''
        })
        const loading = ref(false)
        const error = ref('')
        const errors = ref({
            surname: '',
            firstname: '',
            patronymic: '',
            email: '',
            password: '',
            password_confirmation: ''
        })

        const handleSubmit = async () => {
            error.value = ''
            errors.value = {
                surname: '',
                firstname: '',
                patronymic: '',
                email: '',
                password: '',
                password_confirmation: ''
            }

            loading.value = true
            try {
                await authService.register(form)
                router.push('/').then(() => window.location.reload())
            } catch (err) {
                if (err.response?.data?.errors) {
                    errors.value = err.response.data.errors
                } else {
                    error.value = 'Ошибка регистрации'
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
