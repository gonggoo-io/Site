<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import Container from './components/Container.vue';
</script>

<template>
    <Head title="gonggoo" />
    <Header />
    <main class="py-10">
        <Container>
            <div>
                <h1 class="text-3xl font-bold mb-4">gonggoo with amuz Project</h1>
                <p class="text-lg text-gray-600">Laravel + Vue 3 + Inertia.js</p>
            </div>
        </Container>
    </main>
    <Footer />
</template>