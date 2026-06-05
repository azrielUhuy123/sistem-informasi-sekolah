import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Informasi from '../pages/Informasi.vue'
import Prestasi from '../pages/Prestasi.vue'
import PPDB from '../pages/PPDB.vue'
import Kontak from '../pages/Kontak.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/informasi', component: Informasi },
    { path: '/prestasi', component: Prestasi },
    { path: '/ppdb', component: PPDB },
    { path: '/kontak', component: Kontak },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})