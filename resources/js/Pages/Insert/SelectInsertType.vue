<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />
    <main class="flex-grow flex flex-col items-center justify-center pt-32 px-4">
      <h1 class="text-2xl font-bold mb-8">공구 등록 방식 선택</h1>
      <div class="w-full max-w-md grid grid-cols-1 gap-6">
        <button @click="goToInsert" class="w-full bg-white rounded-xl shadow p-6 flex flex-col items-center hover:bg-gray-100 transition">
          <span class="text-lg font-semibold mb-2">직접 입력하기</span>
          <span class="text-gray-500 text-sm">폼을 직접 작성해서 공구를 등록합니다.</span>
        </button>
        <button @click="goToAutoInsert" class="w-full bg-white rounded-xl shadow p-6 flex flex-col items-center hover:bg-gray-100 transition">
          <span class="text-lg font-semibold mb-2">자동으로 입력하기</span>
          <span class="text-gray-500 text-sm">상품 링크만 입력하면 정보를 자동으로 불러옵니다.</span>
        </button>
      </div>
    </main>
  </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import { router } from '@inertiajs/vue3';

const goToInsert = () => {
  router.visit('/insert');
};
const goToAutoInsert = () => {
  router.visit('/auto-insert');
};
</script> 