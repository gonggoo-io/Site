<template>
  <footer :class="['bg-[#f3f4f6] py-8 w-full', { 'fixed bottom-0 left-0 right-0': shouldFixFooter }]">
    <Container :flex="true">
      <div class="flex items-center gap-6">
        <img :src="logoImage" alt="logo" class="w-5 h-5 filter grayscale opacity-60" />
        <div class="flex flex-col gap-1">
          <span class="text-xs text-gray-500">© 2025 gonggoo. All rights reserved.</span>
          <span class="text-xs text-gray-500">Developed by <a href="https://github.com/siniseong" target="_blank" class="transition-colors">siniseong to amuz</a></span>
        </div>
      </div>
      <div class="flex items-center">
        <a href="https://github.com/gonggoo-io/Site" target="_blank" class="hover:opacity-80 transition-opacity">
          <img :src="githubLogoImage" alt="GitHub" class="w-8 h-8 filter grayscale opacity-60" />
        </a>
      </div>
    </Container>
  </footer>
</template>

<script setup>  
import { ref, onMounted } from 'vue';
import Container from './Container.vue';
import logoImage from '/public/images/logo.svg';
import githubLogoImage from '/public/images/github-logo.svg';

const shouldFixFooter = ref(false);

onMounted(() => {
  const checkContentHeight = () => {
    const contentHeight = document.documentElement.scrollHeight;
    const windowHeight = window.innerHeight;
    shouldFixFooter.value = contentHeight <= windowHeight;
  };

  checkContentHeight();
  window.addEventListener('resize', checkContentHeight);
});
</script> 