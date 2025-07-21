<template>
  <div 
    v-if="isVisible && isMobile" 
    class="fixed bottom-5 left-4 right-4 z-50"
    :class="{ 'animate-slide-up': isVisible }"
  >
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 py-8 relative">
      <button 
        @click="closeBanner"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
        aria-label="배너 닫기"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="text-center mb-4 mt-4">
        <h3 class="text-lg font-medium text-gray-800 leading-tight">
          아래 버튼을 누르면<br>공구에 대한 설명을 확인할 수 있어요.
        </h3>
      </div>
      
      <div class="text-center mb-2">
        <button 
          @click="viewDetails"
          class="w-full bg-[#2F9266] text-white px-6 py-3 rounded-xl text-base font-medium hover:bg-[#247A4F] transition-colors"
        >
          자세히 보기
        </button>
      </div>

      <div class="text-center">
        <button 
          @click="closeBanner"
          class="text-sm text-gray-500 hover:text-gray-700 transition-colors underline"
        >
          다음에 볼래요.
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  detailUrl: {
    type: String,
    default: '/signup'
  },
  pdfFile: {
    type: String,
    default: null
  }
})

const isVisible = ref(false)
const isMobile = ref(false)

const checkMobile = () => {
  isMobile.value = window.innerWidth < 768
}

const showBanner = () => {
  setTimeout(() => {
    if (isMobile.value) {
      isVisible.value = true
    }
  }, 1000)
}

const closeBanner = () => {
  isVisible.value = false
}

const goToHome = () => {
  router.visit('/dashboard')
}

const viewDetails = () => {
  if (props.pdfFile) {
    // PDF 파일을 새 창에서 열기
    window.open(`/documents/${props.pdfFile}`, '_blank')
  } else {
    // 기본 페이지로 이동
    router.visit(props.detailUrl)
  }
}

const handleResize = () => {
  checkMobile()
  if (!isMobile.value) {
    isVisible.value = false
  }
}

onMounted(() => {
  checkMobile()
  showBanner()
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
@keyframes slide-up {
  from {
    transform: translateY(100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.animate-slide-up {
  animation: slide-up 0.3s ease-out;
}

@media (min-width: 768px) {
  .fixed {
    display: none !important;
  }
}
</style> 