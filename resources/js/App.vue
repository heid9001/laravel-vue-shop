<template>
  <div class="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <router-link class="navbar-brand" :to="{ name: 'products' }">Lara Shop</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" :to="{ name: 'products' }">Продукты</router-link>
            </li>
            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" :to="{ name: 'orders' }">Мои заказы</router-link>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <router-link :to="{ name: 'cart' }" class="btn btn-outline-light position-relative me-3">
              <i class="bi bi-cart3"></i>
              <span v-if="cartItemCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ cartItemCount }}
              </span>
            </router-link>
            <router-link v-if="!isAuthenticated" class="btn btn-outline-light" :to="{ name: 'register' }">Регистрация</router-link>
            <router-link v-if="!isAuthenticated" :to="{ name: 'login' }" class="btn btn-outline-light">Войти</router-link>
            <button v-if="isAuthenticated" @click="logout" class="btn btn-outline-light">Выйти</button>
          </div>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from './services/api'
import { cartService } from './services/cart'

export default {
  name: 'App',
  setup() {
    const router = useRouter()
    const isAuthenticated = ref(false)
    const cartItemCount = ref(0)

    const checkAuth = () => {
      isAuthenticated.value = !!localStorage.getItem('access_token')
    }

    const updateCartCount = () => {
      cartItemCount.value = cartService.getItemCount()
    }

    const logout = async () => {
      try {
        await authService.logout()
        localStorage.removeItem('access_token')
        isAuthenticated.value = false
        router.push({ name: 'login' }).then(() => window.location.reload())
      } catch (error) {
        console.error('Logout failed:', error)
      }
    }

    const handleStorageChange = (e) => {
      if (e.key === 'access_token') {
        checkAuth()
      } else if (e.key === 'cart') {
        updateCartCount()
      }
    }

    onMounted(() => {
      checkAuth()
      updateCartCount()
      window.addEventListener('storage', handleStorageChange)
    })

    onUnmounted(() => {
      window.removeEventListener('storage', handleStorageChange)
    })

    return {
      isAuthenticated,
      cartItemCount,
      logout
    }
  }
}
</script>

<style>
.app {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.navbar {
  margin-bottom: 2rem;
}

.badge {
  font-size: 0.75rem;
  transform: translate(-50%, -50%) !important;
}
</style>
