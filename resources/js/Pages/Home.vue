<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './components/Header.vue';
</script>

<template>
    <Head title="gonggoo" />
    <Header />
    <main class="py-10">
        <div class="container mx-auto px-4">
            <div class="w-full max-w-[1440px] mx-auto">
                <h1 class="text-3xl font-bold mb-4">gonggoo with amuz Project</h1>
                <p class="text-lg text-gray-600">Laravel + Vue 3 + Inertia.js</p>
            </div>
        </div>
    </main>
</template>