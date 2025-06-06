import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

console.log('Vue app initializing...');

const app = createApp(App);
app.use(router);
app.mount('#app');
