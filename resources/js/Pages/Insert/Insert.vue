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
                쿠팡, G마켓, 옥션에서 구입하지 않는다면?
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
              <p class="px-4 text-2xl text-black font-semibold">간단한 물품 정보에 대해서 입력해주세요.</p>
              <p class="px-4 text-base text-gray-500 mb-6">입력을 완료했으면 '다음' 버튼을 클릭해주세요.</p>
            </div>
            <form @submit.prevent="goToNext" class="px-4 space-y-6">
              <div class="form-group">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                  물품 제목 <span class="text-red-500">*</span>
                </label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  placeholder="예: 아이폰 15 Pro 256GB 자연 티타늄"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  required
                />
              </div>
              <div class="form-group">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                  물품 설명
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  placeholder="물품에 대한 자세한 설명을 입력해주세요."
                  rows="4"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none"
                ></textarea>
              </div>
              <div class="form-group">
                <label for="link" class="block text-sm font-semibold text-gray-700 mb-2">
                  구매 링크 <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    id="link"
                    v-model="form.link"
                    type="url"
                    placeholder="https://example.com/product"
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                    required
                  />
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                  총 가격 <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    id="price"
                    v-model="form.price"
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

              <div class="pt-4 pb-6">
                <button
                  type="submit"
                  class="w-full bg-gradient-to-r from-[#2F9266] to-[#34A373] text-white py-3 px-6 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                  :disabled="!isFormValid"
                >
                  <span class="flex items-center justify-center gap-2">
                    다음 단계로 (1/3)
                  </span>
                </button>
              </div>
            </form>
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
import { ref, computed } from 'vue';
import Header from '../components/Header.vue';
import Container from '../components/Container.vue';
import { router } from '@inertiajs/vue3';

const form = ref({
  title: '',
  description: '',
  link: '',
  price: ''
});

const isFormValid = computed(() => {
  return form.value.title.trim() !== '' && 
         form.value.link.trim() !== '' && 
         form.value.price > 0;
});

const goToNext = () => {
  if (isFormValid.value) {
    router.visit('/insert-details', {
      data: form.value
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