<template>
  <header class="bg-white py-4">
    <Container>
      <div class="flex items-center gap-3">
        <img src="/public/logo.svg" alt="logo" class="w-5 h-5" />
        <span class="text-xl font-bold text-black">공구</span>
      </div>
      <div class="flex gap-3">
        <button class="px-3.5 py-1.5 text-[14px] border border-gray-300 rounded-md hover:bg-gray-100 hover:text-gray-800 transition-colors">
          로그인
        </button>
        <button class="px-3.5 py-1.5 text-[14px] border border-[#2F9266] bg-[#2F9266] text-white rounded-md hover:bg-[#247A4F] hover:border-[#247A4F] transition-colors">
          회원가입
        </button>
      </div>
    </Container>
  </header>
</template>

<script setup>
import Container from './Container.vue';
</script>
