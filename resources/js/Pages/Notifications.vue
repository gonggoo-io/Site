<template>
    <Header />
    <main class="pt-[72px] min-h-screen bg-gray-50">
      <div class="w-full max-w-md md:max-w-2xl lg:max-w-3xl xl:max-w-4xl mx-auto py-6 px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">알림</h1>
        <div v-if="notifications.length > 0" class="space-y-2">
          <div v-for="notification in notifications" :key="notification.id" class="bg-white rounded-xl p-4 cursor-pointer flex items-center" @click="goToContent(notification.insert_id)">
            <div class="flex items-center space-x-3 w-full">
              <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                <span class="text-xl font-semibold text-white">s</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-gray-500 truncate max-w-[220px]">{{ notification.title }}</div>
                <div :class="['text-gray-black text-sm font-medium', { 'font-bold': !notification.read_at }]">
                  {{ notification.user }}님이 {{ notification.group }}공구를 {{ notification.action }}하셨습니다.
                  <span v-if="!notification.read_at" class="inline-block ml-2 w-2 h-2 bg-red-500 rounded-full align-middle"></span>
                </div>
                <div class="text-xs text-gray-400">{{ notification.date }}</div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="flex items-center justify-center py-20">
          <div class="text-gray-400 text-center">
            <div class="text-lg text-gray-600">온 알림이 없어요</div>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </template>
  
  <script setup>
  import Header from './components/Header.vue';
  import Footer from './components/Footer.vue';
  import { ref, computed, onMounted } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import axios from 'axios';
  
  const page = usePage();
  const userName = computed(() => page.props.auth?.user?.name || '나');
  
  const notifications = ref([]);
  
  onMounted(async () => {
    const res = await axios.get('/notifications');
    notifications.value = res.data;
  });
  
  function goToContent(insertId) {
    if (insertId) {
      router.visit(`/content/${insertId}`);
    }
  }
  </script>
  
  <style scoped>
  main {
    background: #F6F6F6;
  }
  </style>