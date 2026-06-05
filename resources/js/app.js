import './bootstrap';
import 'bootstrap';

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

createApp(App)
    .use(router)
    .mount('#app')

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
