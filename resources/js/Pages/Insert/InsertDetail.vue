<template>
    <div class="min-h-screen bg-white flex flex-col overflow-y-auto">
      <Header />
      <Container>
        <main class="flex-grow flex flex-col md:flex-row items-center justify-between pt-32 transition-colors duration-300">
          <div class="w-full md:w-[50%] flex items-center justify-center mb-12 md:mb-40">
            <transition name="slide-fade">
              <div class="flex flex-col items-center" key="question">
                <img src="/public/images/insert-search.png" alt="section choice" class="w-60 hidden md:block" />
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 animate-fadein text-center">
                  물품 정보 > <span class="text-[#2F9266]">세부 정보</span> > 입금 정보
                </h1>
                <div class="w-full text-center text-gray-500 mt-1 mb-[-2] text-base md:text-lg">
                  공구에 참여하는 분들을 위해 세부 내용을 작성해요.
                </div>
              </div>
            </transition>
          </div>
          <div class="w-full md:w-[50%] flex flex-col items-start">
            <div class="w-full max-w-md mx-auto scrollable-area overflow-y-auto" style="max-height: calc(100vh - 200px);">
              <div class="sticky top-0 z-10 bg-white pt-4 pb-2">
                <p class="px-4 text-2xl text-black font-semibold">세부 정보 (모집 인원, 배송 위치, 1인당 가져가는 개수)에 대해서 알려주세요.</p>
                <p class="px-4 text-base text-gray-500 mb-6">입력을 완료했으면 '다음' 버튼을 클릭해주세요.</p>
              </div>
              <form @submit.prevent="goToNext" class="px-4 space-y-6">
                <div class="form-group">
                  <label for="peopleCount" class="block text-sm font-semibold text-gray-700 mb-2">
                    모집 인원 <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      id="peopleCount"
                      v-model="form.peopleCount"
                      type="number"
                      min="1"
                      step="1"
                      placeholder="예: 10"
                      class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                      required
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 font-medium">명</span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="perPersonCount" class="block text-sm font-semibold text-gray-700 mb-2">
                    1인당 가져가는 개수 <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      id="perPersonCount"
                      v-model="form.perPersonCount"
                      type="number"
                      min="1"
                      step="1"
                      placeholder="예: 1"
                      class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                      required
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 font-medium">개</span>
                    </div>
                  </div>
                </div>
                <KakaoMap v-model:address="form.address" input-class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white" />
                <div class="pt-4 pb-6">
                  <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#2F9266] to-[#34A373] text-white py-3 px-6 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    :disabled="!isFormValid"
                  >
                    <span class="flex items-center justify-center gap-2">
                      다음 단계로 (2/3)
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
  import KakaoMap from './KakaoMap.vue';
  
  const form = ref({
    peopleCount: '',
    perPersonCount: '',
    address: ''
  });
  
  const isFormValid = computed(() => {
    return form.value.peopleCount > 0 && form.value.perPersonCount > 0 && form.value.address;
  });
  
  const goToNext = () => {
    if (isFormValid.value) {

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