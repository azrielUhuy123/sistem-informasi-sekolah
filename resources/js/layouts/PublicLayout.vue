<template>

    <Navbar />

    <main>
        <slot />
    </main>

    <Footer />

</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
</script>