<template>
  <header
    class="bg-white py-4 border-b transition-all duration-200"
    :class="{ 'border-b': currentPath !== '/' }"
  >
    <Container class="flex items-center justify-between">
      <Link href="/" class="flex items-center gap-2 hover:opacity-80 transition">
        <img src="/public/logo.svg" alt="logo" class="w-5 h-5" />
        <span class="text-xl font-bold text-[#2F9266]">공구</span>
      </Link>

      <div class="flex gap-3">
        <Link
          href="/signin"
          class="px-3.5 py-1.5 text-[14px] border border-gray-300 rounded-md hover:bg-gray-100 hover:text-gray-800 transition-colors"
        >
          로그인
        </Link>
        <Link
          href="/signup"
          class="px-3.5 py-1.5 text-[14px] border border-[#2F9266] bg-[#2F9266] text-white rounded-md hover:bg-[#247A4F] hover:border-[#247A4F] transition-colors"
        >
          회원가입
        </Link>
      </div>
    </Container>
  </header>
</template>

<script setup>
import Container from './Container.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
const page = usePage();
const currentPath = computed(() => page.url);
</script>
