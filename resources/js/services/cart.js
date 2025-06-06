import { ref } from 'vue'

export const CART_STORAGE_KEY = 'cart'

export const cartService = {
    getCart() {
        const cart = localStorage.getItem(CART_STORAGE_KEY)
        return cart ? JSON.parse(cart) : []
    },

    addToCart(product, quantity = 1) {
        const cart = this.getCart()
        const existingItem = cart.find(item => item.id === product.id)

        if (existingItem) {
            existingItem.quantity += quantity
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                quantity
            })
        }

        this.saveCart(cart)
    },

    removeFromCart(productId) {
        const cart = this.getCart()
        const updatedCart = cart.filter(item => item.id !== productId)
        this.saveCart(updatedCart)
    },

    updateQuantity(productId, quantity) {
        if (quantity < 1) return

        const cart = this.getCart()
        const item = cart.find(item => item.id === productId)

        if (item) {
            item.quantity = quantity
            this.saveCart(cart)
        }
    },

    clearCart() {
        localStorage.removeItem(CART_STORAGE_KEY)
    },

    getTotal() {
        const cart = this.getCart()
        return cart.reduce((total, item) => total + (item.price * item.quantity), 0)
    },

    getItemCount() {
        const cart = this.getCart()
        return cart.reduce((count, item) => count + item.quantity, 0)
    },

    saveCart(cart) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart))
        window.dispatchEvent(new Event('cart-updated'))
    }
}
