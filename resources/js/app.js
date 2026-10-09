import './bootstrap';
import 'bootstrap';

import './confirm-modal';

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

const appElement = document.getElementById('app');

if (appElement) {
    createApp(App)
        .use(router)
        .mount(appElement);
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
