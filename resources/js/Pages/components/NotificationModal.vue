<template>
  <transition name="fade">
    <div v-if="open" class="fixed top-16 right-6 z-50 w-full max-w-sm bg-gray-50 rounded-2xl shadow-2xl border border-gray-200 overflow-hidden animate-fadein">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
        <slot name="header">
          <span class="font-bold text-lg text-gray-900">알림</span>
        </slot>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
      </div>
      <div class="max-h-[60vh] overflow-y-auto px-4 py-3">
        <div v-if="notifications.length > 0" class="space-y-3">
          <div v-for="notification in notifications" :key="notification.id" class="bg-white rounded-xl p-4 border border-gray-100 cursor-pointer flex items-center" @click="goToContent(notification.insert_id)">
            <div class="flex items-center space-x-3 w-full">
              <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                <span class="text-xl font-semibold text-white">s</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-gray-500 truncate max-w-[220px]">{{ notification.title }}</div>
                <div :class="['text-gray-black text-sm font-medium', { 'font-bold': !notification.read_at }]">
                  <div v-if="notification.type === 'purchase_completed'">
                    <div class="font-semibold mb-1">{{ notification.user }}님이 {{ notification.group }}공구를 {{ notification.action }}하셨습니다.</div>
                    <div class="text-xs text-gray-600 space-y-1">
                      <div><strong>택배사:</strong> {{ notification.courier }}</div>
                      <div><strong>운송장번호:</strong> {{ notification.tracking_number }}</div>
                      <div><strong>입금 정보:</strong> {{ notification.bank }} {{ notification.account_number }}</div>
                    </div>
                  </div>
                  <div v-else>
                    {{ notification.user }}님이 {{ notification.group }}공구를 {{ notification.action }}하셨습니다.
                  </div>
                </div>
                <div class="text-xs text-gray-400">{{ notification.date }}</div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="flex items-center justify-center py-10">
          <span class="text-gray-400">온 알림이 없어요</span>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
  open: Boolean,
  notifications: {
    type: Array,
    default: () => []
  }
});
defineEmits(['close']);

function goToContent(insertId) {
  if (insertId) {
    router.visit(`/content/${insertId}`);
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.animate-fadein {
  animation: fadeInUp 0.3s;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style> 