<template>
    <Header />
    <main class="pt-[72px] min-h-screen bg-gray-50">
      <div class="w-full max-w-md md:max-w-2xl lg:max-w-3xl xl:max-w-4xl mx-auto py-6 px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">알림</h1>
        <div v-if="notifications.length > 0" class="space-y-2">
          <div v-for="notification in notifications" :key="notification.id" class="bg-white rounded-xl p-4">
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 w-10 h-10 bg-[#2F9266] rounded-full flex items-center justify-center">
                <span class="text-xl font-semibold text-white">s</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-gray-500">[11번가] 삼성 QLED TV KQ75QD63AFXKR 189cm(75인치) 4K 사운드바 무상증정</div>
                <div class="text-gray-black text-sm font-medium">
                  {{ notification.user }}님이 {{ notification.group }}공구를 {{ notification.action }}하셨습니다.
                </div>
                <div class="text-xs text-gray-400">
                  {{ notification.date }}
                </div>
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
  import { usePage } from '@inertiajs/vue3';
  import axios from 'axios';
  
  const page = usePage();
  const userName = computed(() => page.props.auth?.user?.name || '나');
  
  const notifications = ref([]);
  
  onMounted(async () => {
    const res = await axios.get('/notifications');
    notifications.value = res.data;
  });
  </script>
  
  <style scoped>
  main {
    background: #F6F6F6;
  }
  </style>