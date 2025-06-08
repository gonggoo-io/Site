<template>
  <footer class="bg-[#f3f4f6] py-8 fixed bottom-0 left-0 right-0 w-full">
    <Container>
      <div class="flex flex-col gap-1">
        <span class="text-xs text-gray-500">© 2025 gonggoo. All rights reserved.</span>
        <span class="text-xs text-gray-500">Developed by <a href="https://github.com/siniseong" target="_blank" class="transition-colors">siniseong</a></span>
      </div>
      <div class="flex items-center">
        <a href="https://github.com/gonggoo-io/Site" target="_blank" class="hover:opacity-80 transition-opacity">
          <img src="/public/github-logo.svg" alt="GitHub" class="w-8 h-8 filter grayscale opacity-60" />
        </a>
      </div>
    </Container>
  </footer>
</template>

<script setup>
import Container from './Container.vue';
</script> 