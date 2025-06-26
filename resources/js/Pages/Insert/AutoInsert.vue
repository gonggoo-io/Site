<template>
  <div class="min-h-screen bg-white flex flex-col overflow-y-auto">
    <Header />
    <Container>
      <main class="flex-grow flex flex-col md:flex-row items-center justify-between pt-32 transition-colors duration-300">
        <div class="w-full md:w-[50%] flex items-center justify-center mb-12 md:mb-40">
          <transition name="slide-fade">
            <div class="flex flex-col items-center" key="question">
              <img src="/public/images/insert-paper.png" alt="section choice" class="w-60 mb-6 hidden md:block" />
              <h1 class="text-2xl md:text-3xl font-bold text-gray-800 animate-fadein text-center">
                <span class="text-[#2F9266]">물품 정보</span> > 세부 정보 > 입금 정보
              </h1>
              <div class="w-full text-center text-gray-500 mt-1 mb-[-2] text-base md:text-lg">
                링크를 입력하면 자동으로 정보를 불러와요.
              </div>
              <p class="px-4 text-base text-gray-500 flex items-center gap-2">
                <button
                  class="ml-2 text-[#2F9266] hover:text-[#247A4F] transition-colors duration-200"
                  @click="goBack"
                  type="button"
                >
                  이전으로 돌아가기
                </button>
              </p>
            </div>
          </transition>
        </div>
        <div class="w-full md:w-[50%] flex flex-col items-start">
          <div class="w-full max-w-md mx-auto scrollable-area overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <div class="sticky top-0 z-10 bg-white pt-4 pb-2">
              <p class="px-4 text-2xl text-black font-semibold">구매 링크를 입력해주세요.</p>
              <p class="px-4 text-base text-gray-500 mb-6">확인 버튼을 누르면 자동으로 정보를 불러옵니다.</p>
            </div>
            <div class="px-4 space-y-6">
              <div class="form-group">
                <label for="link" class="block text-sm font-semibold text-gray-700 mb-2">
                  구매 링크 <span class="text-red-500">*</span>
                </label>
                <div class="relative flex gap-2">
                  <input
                    id="link"
                    v-model="form.link"
                    type="text"
                    placeholder="https://"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                    @keyup.enter="fetchMetadata"
                  />
                  <button
                    type="button"
                    @click="fetchMetadata"
                    :disabled="isLoading || !form.link.trim()"
                    class="px-4 py-2 bg-[#2F9266] text-white rounded-xl hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div v-if="isLoading" class="flex items-center">
                      <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    </div>
                    <span v-else>확인</span>
                  </button>
                </div>
              </div>

              <div class="space-y-6" :class="{ 'invisible': !hasOgData }">
                <div class="form-group">
                  <label for="ogTitle" class="block text-sm font-semibold text-gray-700 mb-2">제목</label>
                  <input
                    id="ogTitle"
                    v-model="ogData.title"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] bg-gray-50 hover:bg-white"
                  />
                </div>
                <div class="form-group">
                  <label for="ogDescription" class="block text-sm font-semibold text-gray-700 mb-2">설명</label>
                  <textarea 
                    id="ogDescription"
                    v-model="ogData.description"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] bg-gray-50 hover:bg-white resize-none"
                  ></textarea>
                </div>
                <div class="form-group">
                  <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                    총 가격 <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      id="price"
                      v-model="ogData.price"
                      type="number"
                      min="0"
                      step="1000"
                      placeholder="예: 1200000"
                      class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                      required
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 font-medium">원</span>
                    </div>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">
                    배송비 포함 총 금액을 입력해주세요
                  </p>
                </div>
              </div>

              <div class="pt-4 pb-6" :class="{ 'invisible': !hasOgData }">
                <button
                  type="button"
                  @click="goToNext"
                  :disabled="!isFormValid"
                  class="w-full bg-gradient-to-r from-[#2F9266] to-[#34A373] text-white py-3 px-6 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                다음 단계로 (1/3)
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </Container>
    <div class="mt-auto">
      <p class="text-center text-sm text-gray-500 py-4 mb-2">© 2025 gonggoo. All rights reserved.</p>
    </div>
  </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import Container from '../components/Container.vue';
import { ref, computed } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const form = ref({
  link: ''
})

const fullLink = computed(() => {
  if (!form.value.link) return ''
  if (form.value.link.startsWith('http://') || form.value.link.startsWith('https://')) {
    return form.value.link
  }
  return `https://${form.value.link}`
})

const ogData = ref({
  title: '',
  description: '',
  price: null,
})

const hasOgData = computed(() => {
  return !!(ogData.value.title || ogData.value.description)
})

const isFormValid = computed(() => {
  return ogData.value.price && ogData.value.price > 0;
});

const isLoading = ref(false)

const fetchMetadata = async () => {
  if (!form.value.link) return;
  
  isLoading.value = true;
  try {
    const response = await axios.post('/api/meta', {
      url: fullLink.value
    });
    
    const metaData = response.data.data || response.data;
    
    ogData.value = {
      title: metaData.title || '',
      description: metaData.description || '',
      price: null,
    };
  } catch (error) {
    alert('링크 정보를 가져오는데 실패했습니다. 개발자 콘솔을 확인해주세요.');
  } finally {
    isLoading.value = false;
  }
}

const goToNext = () => {
  if (isFormValid.value) {
    router.visit('/insert-details', {
      data: {
        link: fullLink.value,
        title: ogData.value.title,
        description: ogData.value.description,
        price: ogData.value.price,
      }
    });
  }
};

const goBack = () => {
  window.history.back();
};
</script>

<style scoped>
.card-animate {
  animation: fadeInUp 0.4s ease forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.scrollable-area::-webkit-scrollbar {
  display: none;
}

.form-group {
  animation: slideInUp 0.6s ease forwards;
}

.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.2s; }
.form-group:nth-child(3) { animation-delay: 0.3s; }
.form-group:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>